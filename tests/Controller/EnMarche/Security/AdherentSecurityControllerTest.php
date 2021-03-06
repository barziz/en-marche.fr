<?php

namespace Tests\AppBundle\Controller\EnMarche\Security;

use AppBundle\DataFixtures\ORM\LoadAdherentData;
use AppBundle\Entity\Adherent;
use AppBundle\Mailer\Message\AdherentResetPasswordConfirmationMessage;
use AppBundle\Mailer\Message\AdherentResetPasswordMessage;
use AppBundle\Repository\AdherentRepository;
use AppBundle\Repository\EmailRepository;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\AppBundle\Controller\ControllerTestTrait;

/**
 * @group functional
 * @group security
 */
class AdherentSecurityControllerTest extends WebTestCase
{
    use ControllerTestTrait;

    /* @var AdherentRepository */
    private $adherentRepository;

    /* @var EmailRepository */
    private $emailRepository;

    public function testAuthenticationIsSuccessful(): void
    {
        $crawler = $this->client->request(Request::METHOD_GET, '/connexion');

        $this->assertResponseStatusCode(Response::HTTP_OK, $this->client->getResponse());
        $this->assertCount(0, $crawler->filter('#auth-error'));

        $this->client->submit($crawler->selectButton('Connexion')->form([
            '_login_email' => 'carl999@example.fr',
            '_login_password' => LoadAdherentData::DEFAULT_PASSWORD,
        ]));

        $adherent = $this->adherentRepository->findOneByEmail('carl999@example.fr');

        $this->assertResponseStatusCode(Response::HTTP_FOUND, $this->client->getResponse());
        $this->assertClientIsRedirectedTo('/evenements', $this->client);
        $this->assertInstanceOf(\DateTime::class, $adherent->getLastLoggedAt());

        $crawler = $this->client->followRedirect();
        $this->assertResponseStatusCode(Response::HTTP_OK, $this->client->getResponse());
        $this->assertSame(1, $crawler->selectLink('Carl Mirabeau')->count());

        $this->client->click($crawler->selectLink('Déconnexion')->link());
        $this->assertResponseStatusCode(Response::HTTP_FOUND, $this->client->getResponse());
        $this->assertClientIsRedirectedTo('/', $this->client, true);

        $crawler = $this->client->followRedirect();
        $this->assertSame(0, $crawler->selectLink('Carl Mirabeau')->count());
    }

    /**
     * @dataProvider provideInvalidCredentials
     */
    public function testLoginCheckFails(string $username, string $password, string $messageExpected): void
    {
        $crawler = $this->client->request(Request::METHOD_GET, '/connexion');

        $this->assertResponseStatusCode(Response::HTTP_OK, $this->client->getResponse());
        $this->assertCount(0, $crawler->filter('#auth-error'));

        $this->client->submit($crawler->selectButton('Connexion')->form([
            '_login_email' => $username,
            '_login_password' => $password,
        ]));

        $this->assertResponseStatusCode(Response::HTTP_FOUND, $this->client->getResponse());
        $this->assertClientIsRedirectedTo('/connexion', $this->client);

        $crawler = $this->client->followRedirect();

        $this->assertResponseStatusCode(Response::HTTP_OK, $this->client->getResponse());
        $this->assertCount(1, $error = $crawler->filter('#auth-error'));
        $this->assertSame($messageExpected, trim($error->text()));
    }

    public function provideInvalidCredentials(): array
    {
        return [
            'Unregistered adherent account' => [
                'foobar@foo.tld',
                'foo-bar-pass',
                'L\'adresse e-mail et le mot de passe que vous avez saisis ne correspondent pas.',
            ],
            'Registered enabled adherent' => [
                'carl999@example.fr',
                'foo-bar-pass',
                'L\'adresse e-mail et le mot de passe que vous avez saisis ne correspondent pas.',
            ],
            'Registered not validated account' => [
                'michelle.dufour@example.ch',
                'secret!12345',
                'Pour vous connecter vous devez confirmer votre adhésion. Si vous n\'avez pas reçu le mail de validation, vous pouvez cliquer ici pour le recevoir à nouveau.',
            ],
            'Registered disabled account' => [
                'simple-user-disabled@example.ch',
                'secret!12345',
                'Account is disabled.',
            ],
        ];
    }

    public function testRetrieveForgotPasswordAction(): void
    {
        $crawler = $this->client->request(Request::METHOD_GET, '/mot-de-passe-oublie');

        $this->assertResponseStatusCode(Response::HTTP_OK, $this->client->getResponse());

        $this->assertCount(1, $crawler->filter('input[name="form[email]"]'));
        $this->assertCount(0, $crawler->filter('.form__error'), 'No error should be displayed on initial display');
    }

    public function testRetrieveForgotPasswordActionWithEmptyEmail(): void
    {
        $crawler = $this->client->request(Request::METHOD_GET, '/mot-de-passe-oublie');

        $this->assertResponseStatusCode(Response::HTTP_OK, $this->client->getResponse());

        $crawler = $this->client->submit($crawler->selectButton('Envoyer un e-mail')->form(), ['form' => ['email' => '']]);

        $this->assertResponseStatusCode(Response::HTTP_OK, $this->client->getResponse());

        $this->assertCount(1, $crawler->filter('input[name="form[email]"]'));
        $this->assertCount(1, $error = $crawler->filter('.form__error'));
        $this->assertContains('Cette valeur ne doit pas être vide.', $error->text(), 'An empty email should be erroneous.');
    }

    public function testRetrieveForgotPasswordActionWithUnknownEmail(): void
    {
        $crawler = $this->client->request(Request::METHOD_GET, '/mot-de-passe-oublie');

        $this->assertResponseStatusCode(Response::HTTP_OK, $this->client->getResponse());

        $formData = [
            'form' => ['email' => 'toto@example.org'],
        ];

        $this->client->submit($crawler->selectButton('Envoyer un e-mail')->form(), $formData);

        $this->assertClientIsRedirectedTo('/connexion', $this->client);

        $crawler = $this->client->followRedirect();

        $this->assertResponseStatusCode(Response::HTTP_OK, $this->client->getResponse());
        $this->assertCount(0, $crawler->filter('.form__error'));
        $this->assertContains('Un e-mail vous a été envoyé contenant un lien pour réinitialiser votre mot de passe.', $crawler->text());
        $this->assertCount(0, $this->emailRepository->findRecipientMessages(AdherentResetPasswordMessage::class, 'toto@example.org'), 'No mail should have been sent to unknown account.');
    }

    public function testRetrieveForgotPasswordActionWithKnownEmailSendEmail(): void
    {
        $crawler = $this->client->request(Request::METHOD_GET, '/mot-de-passe-oublie');

        $this->assertResponseStatusCode(Response::HTTP_OK, $this->client->getResponse());

        $formData = [
            'form' => ['email' => 'carl999@example.fr'],
        ];

        $this->client->submit($crawler->selectButton('Envoyer un e-mail')->form(), $formData);

        $this->assertClientIsRedirectedTo('/connexion', $this->client);

        $crawler = $this->client->followRedirect();

        $this->assertResponseStatusCode(Response::HTTP_OK, $this->client->getResponse());
        $this->assertCount(0, $crawler->filter('.form__error'));
        $this->assertContains('Un e-mail vous a été envoyé contenant un lien pour réinitialiser votre mot de passe.', $crawler->text());

        $this->assertCount(1, $this->emailRepository->findRecipientMessages(AdherentResetPasswordMessage::class, 'carl999@example.fr'), 'An email should have been sent.');
    }

    public function testResetPasswordAction(): void
    {
        $client = $this->makeClient(false, ['HTTP_HOST' => $this->hosts['app']]);
        $adherent = $this->getAdherentRepository()->findOneByEmail('michelle.dufour@example.ch');
        $token = $this->getFirstAdherentResetPasswordToken();
        $oldPassword = $adherent->getPassword();

        $this->assertNull($token->getUsageDate());

        $resetPasswordUrl = sprintf('/changer-mot-de-passe/%s/%s', $adherent->getUuid(), $token->getValue());
        $crawler = $client->request(Request::METHOD_GET, $resetPasswordUrl);

        $this->assertResponseStatusCode(Response::HTTP_OK, $client->getResponse());
        $this->assertCount(1, $crawler->filter('input[name="adherent_reset_password[password][first]"]'));
        $this->assertCount(1, $crawler->filter('input[name="adherent_reset_password[password][second]"]'));

        $client->submit($crawler->selectButton('adherent_reset_password[submit]')->form(), [
            'adherent_reset_password' => [
                'password' => [
                    'first' => 'new password',
                    'second' => 'new password',
                ],
            ],
        ]);

        $this->assertCount(1, $this->emailRepository->findRecipientMessages(AdherentResetPasswordConfirmationMessage::class, 'michelle.dufour@example.ch'), 'A confirmation email should have been sent.');
        $this->assertClientIsRedirectedTo('/parametres/mon-compte', $client);

        $client->followRedirect();

        // Refresh the adherent
        $this->getEntityManager(Adherent::class)->refresh($adherent);

        $this->assertNotSame($adherent->getPassword(), $oldPassword);

        // Reset password twice
        $client->request(Request::METHOD_GET, $resetPasswordUrl);

        $this->assertResponseStatusCode(Response::HTTP_NOT_FOUND, $client->getResponse());
    }

    protected function setUp()
    {
        parent::setUp();

        $this->init();

        $this->adherentRepository = $this->getAdherentRepository();
        $this->emailRepository = $this->getEmailRepository();
    }

    protected function tearDown()
    {
        $this->kill();

        $this->emailRepository = null;
        $this->adherentRepository = null;

        parent::tearDown();
    }

    private function getFirstAdherentResetPasswordToken()
    {
        return current($this->getResetPasswordTokenRepository()->findAll());
    }
}
