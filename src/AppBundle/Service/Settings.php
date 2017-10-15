<?php
namespace AppBundle\Service;
/**
 * Created by PhpStorm.
 * User: wakhan
 * Date: 11/12/2016
 * Time: 6:44 PM
 */
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;

class Settings
{

    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;

    }

    /***
     * @param $months array of months
     * @return order_months
     */
    function orderMonths($months) {
        $order_months = array();
        if(is_array($months) && count($months) > 0) {
            $temp = array();
            foreach($months as $month) {
                $m = date_parse($month);
                $temp[$m['month']] = $month;
            }
            ksort($temp);
            $order_months = $temp;
        }

        return $order_months;
    }

    /**
     * @param $table
     * @return array
     */
    public function campaignMenu($table)
    {

        $data = $this->em->createQuery(
            "SELECT ca.id, ca.campaignMonth, ca.campaignType, ca.campaignYear
             FROM AppBundle:$table a
             JOIN a.campaign ca GROUP BY ca.id ORDER BY ca.id DESC"
        )
            ->getResult(Query::HYDRATE_SCALAR);

        return $data;

    }

    /**
     * @param $table
     * @param $campaignId
     * @return single campaign
     */
    public function latestCampaign($table, $campaignId = 0) {

        if($campaignId === 0 || $campaignId == 0) {
            $data = $this->em->createQuery(
                "SELECT ca.id, ca.campaignMonth, ca.campaignType, ca.campaignYear
               FROM AppBundle:$table a
               JOIN a.campaign ca ORDER BY ca.id DESC"
              ) ->setFirstResult(1)
                ->setMaxResults(1)
                ->getResult(Query::HYDRATE_SCALAR);
            //$campaignId = $data[0]['CampID'];
            return $data;
        }
        else {
          $data = $this->em->createQuery(
              "SELECT ca.id, ca.campaignMonth, ca.campaignType, ca.campaignYear
               FROM AppBundle:$table a
               JOIN a.campaign ca WHERE ca.id =:camp"
          )->setParameter('camp', $campaignId)->getResult(Query::HYDRATE_SCALAR);
          return $data;
       }
    }

    /**
     * @param $table
     * @param int $no default 3 campaigns
     * @return array
     */
    public function lastFewCampaigns($table, $no = 3) {
        $campaigns = $this->campaignMenu($table);
        $cam = [];
        $i = 0;
        foreach ($campaigns as $campaign) {
            if($i == $no)
                break;
            $cam[] = $campaign['id'];
            $i++;
        }

        return $cam;
    }

      /**
       * @param $table
       * @return array
       */
      public function noEntryCampaigns($table) {
          $campaigns = $this->campaignMenu($table);
          $cam = [];
          foreach ($campaigns as $campaign) {
            $check = $this->campaignEntryCheck($campaign['id']);
              if(isset($check))
                $cam[] = $campaign['id'];

                $check = [];
          }

          return $cam;
      }

      /**
       * @param $campaignId
       * @return array
       */
      public function campaignEntryCheck($campaignId)
      {

          $data = $this->em->createQuery(
              "SELECT adm FROM AppBundle:AdminData adm WHERE adm.campaign =:camp"
            )->setParameter('camp', $campaignId)
             ->setFirstResult(1)
             ->setMaxResults(1)
             ->getResult(Query::HYDRATE_SCALAR);

          return $data;

      }

}
