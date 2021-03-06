<?php

namespace AppBundle\Entity\ElectedRepresentative;

use MyCLabs\Enum\Enum;

final class PoliticalFunctionNameEnum extends Enum
{
    public const MAYOR = 'mayor';
    public const DEPUTY_MAYOR = 'deputy_mayor';
    public const MAYOR_ASSISTANT = 'mayor_assistant';
    public const PRESIDENT_OF_REGIONAL_COUNCIL = 'president_of_regional_council';
    public const VICE_PRESIDENT_OF_REGIONAL_COUNCIL = 'vice_president_of_regional_council';
    public const PRESIDENT_OF_DEPARTMENTAL_COUNCIL = 'president_of_departmental_council';
    public const VICE_PRESIDENT_OF_DEPARTMENTAL_COUNCIL = 'vice_president_of_departmental_council';
    public const SECRETARY = 'secretary';
    public const QUAESTOR = 'quaestor';
    public const PRESIDENT_OF_NATIONAL_ASSEMBLY = 'president_of_national_assembly';
    public const VICE_PRESIDENT_OF_NATIONAL_ASSEMBLY = 'vice_president_of_national_assembly';
    public const PRESIDENT_OF_SENATE = 'president_of_senate';
    public const VICE_PRESIDENT_OF_SENATE = 'vice_president_of_senate';
    public const PRESIDENT_OF_COMMISSION = 'president_of_commission';
    public const PRESIDENT_OF_GROUP = 'president_of_group';

    public const MAYOR_LABEL = 'Maire';
    public const DEPUTY_MAYOR_LABEL = 'Maire délégué';
    public const MAYOR_ASSISTANT_LABEL = 'Adjoint au maire';
    public const PRESIDENT_OF_REGIONAL_COUNCIL_LABEL = 'Président de conseil régional';
    public const VICE_PRESIDENT_OF_REGIONAL_COUNCIL_LABEL = 'Vice-président de conseil régional';
    public const PRESIDENT_OF_DEPARTMENTAL_COUNCIL_LABEL = 'Président de conseil départemental';
    public const VICE_PRESIDENT_OF_DEPARTMENTAL_COUNCIL_LABEL = 'Vice-président de conseil départemental';
    public const SECRETARY_LABEL = 'Secrétaire';
    public const QUAESTOR_LABEL = 'Questeur';
    public const PRESIDENT_OF_NATIONAL_ASSEMBLY_LABEL = 'Président de l\'Assemblée nationale';
    public const VICE_PRESIDENT_OF_NATIONAL_ASSEMBLY_LABEL = 'Vice-président de l\'Assemblée nationale';
    public const PRESIDENT_OF_SENATE_LABEL = 'Président du Sénat';
    public const VICE_PRESIDENT_OF_SENATE_LABEL = 'Vice-président du Sénat';
    public const PRESIDENT_OF_COMMISSION_LABEL = 'Président de commission';
    public const PRESIDENT_OF_GROUP_LABEL = 'Président de groupe';

    public const CHOICES = [
        self::MAYOR_LABEL => self::MAYOR,
        self::DEPUTY_MAYOR_LABEL => self::DEPUTY_MAYOR,
        self::MAYOR_ASSISTANT_LABEL => self::MAYOR_ASSISTANT,
        self::PRESIDENT_OF_REGIONAL_COUNCIL_LABEL => self::PRESIDENT_OF_REGIONAL_COUNCIL,
        self::VICE_PRESIDENT_OF_REGIONAL_COUNCIL_LABEL => self::VICE_PRESIDENT_OF_REGIONAL_COUNCIL,
        self::PRESIDENT_OF_DEPARTMENTAL_COUNCIL_LABEL => self::PRESIDENT_OF_DEPARTMENTAL_COUNCIL,
        self::VICE_PRESIDENT_OF_DEPARTMENTAL_COUNCIL_LABEL => self::VICE_PRESIDENT_OF_DEPARTMENTAL_COUNCIL,
        self::SECRETARY_LABEL => self::SECRETARY,
        self::QUAESTOR_LABEL => self::QUAESTOR,
        self::PRESIDENT_OF_NATIONAL_ASSEMBLY_LABEL => self::PRESIDENT_OF_NATIONAL_ASSEMBLY,
        self::VICE_PRESIDENT_OF_NATIONAL_ASSEMBLY_LABEL => self::VICE_PRESIDENT_OF_NATIONAL_ASSEMBLY,
        self::PRESIDENT_OF_SENATE_LABEL => self::PRESIDENT_OF_SENATE,
        self::VICE_PRESIDENT_OF_SENATE_LABEL => self::VICE_PRESIDENT_OF_SENATE,
        self::PRESIDENT_OF_COMMISSION_LABEL => self::PRESIDENT_OF_COMMISSION,
        self::PRESIDENT_OF_GROUP_LABEL => self::PRESIDENT_OF_GROUP,
    ];
}
