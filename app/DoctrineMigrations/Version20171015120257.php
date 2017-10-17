<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171015120257 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE catchup_data ADD data_source TINYTEXT DEFAULT NULL, ADD fb_no_sms INT DEFAULT NULL, ADD fb_no_hhs INT DEFAULT NULL, ADD fb_no_u5 INT DEFAULT NULL, ADD fb_no_u5irr INT DEFAULT NULL, ADD fb_guest_vac INT DEFAULT NULL, ADD fb_during_camp_vac INT DEFAULT NULL, ADD fb_refusal_not_vac INT DEFAULT NULL, ADD fb_refusal_vac_during_camp INT DEFAULT NULL, ADD fb_refusal_vac_after_camp INT DEFAULT NULL, ADD fb_child_vac_by_smafter_camp INT DEFAULT NULL, ADD fb_child_not_vacc_after_camp INT DEFAULT NULL, ADD fb_child_missed_inaccessiblity INT DEFAULT NULL, ADD fb_child_refer_ri INT DEFAULT NULL, ADD fb_newborn_rec INT DEFAULT NULL, ADD fb_newborn_opv0 INT DEFAULT NULL, ADD fb_pregnant_rec INT DEFAULT NULL, ADD fb_pregnant_refer_anc INT DEFAULT NULL, ADD vac_absent INT DEFAULT NULL, ADD vac_sleep INT DEFAULT NULL, ADD vac_refusal INT DEFAULT NULL, ADD unrecorded INT DEFAULT NULL, ADD unrecorded_vac INT DEFAULT NULL, DROP vacc_absent, DROP vacc_sleep, DROP vacc_refusal, DROP new_missed, DROP new_vaccinated, DROP new_remaining');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE catchup_data ADD vacc_absent INT DEFAULT NULL, ADD vacc_sleep INT DEFAULT NULL, ADD vacc_refusal INT DEFAULT NULL, ADD new_missed INT DEFAULT NULL, ADD new_vaccinated INT DEFAULT NULL, ADD new_remaining INT DEFAULT NULL, DROP data_source, DROP fb_no_sms, DROP fb_no_hhs, DROP fb_no_u5, DROP fb_no_u5irr, DROP fb_guest_vac, DROP fb_during_camp_vac, DROP fb_refusal_not_vac, DROP fb_refusal_vac_during_camp, DROP fb_refusal_vac_after_camp, DROP fb_child_vac_by_smafter_camp, DROP fb_child_not_vacc_after_camp, DROP fb_child_missed_inaccessiblity, DROP fb_child_refer_ri, DROP fb_newborn_rec, DROP fb_newborn_opv0, DROP fb_pregnant_rec, DROP fb_pregnant_refer_anc, DROP vac_absent, DROP vac_sleep, DROP vac_refusal, DROP unrecorded, DROP unrecorded_vac');
    }
}
