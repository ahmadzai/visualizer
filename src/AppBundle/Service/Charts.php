<?php
namespace AppBundle\Service;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Created by PhpStorm.
 * User: wakhan
 * Date: 11/12/2016
 * Time: 6:44 PM
 */

class Charts
{


    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @param $entity Entity Class Name (within the current bundle)
     * @param $function function in that entity
     * @param $parameters parameters for that function
     * @return mixed
     */
    public function chartData($entity, $function, $parameters, $secondParam = null) {
        $data = $this->em->getRepository('AppBundle:'.$entity)
            ->callMe($function, $parameters, $secondParam);
        return $data;
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

    function shortMonth($month) {
        $months = array('January'=>'Jan',
            'February'=>'Feb',
            'March'=>'Mar',
            'April'=>'Apr',
            'May'=>'May',
            'June'=>'Jun',
            'July'=>'Jul',
            'August'=>'Aug',
            'September'=>'Sept',
            'October'=>'Oct',
            'November'=>'Nov',
            'December'=>'Dec');
        return array_key_exists($month, $months) ? $months[$month] : $month;
    }

    function shortYear($year)
    {
        return strlen($year) == 4 ? substr($year, 2, 2) : $year;
    }

    /***
     * @param mixed: $cat1 array('column'=>'NameOfColumn', 'substitute'=>'Substitute') top level category, only array of length 2
     * @param mixed: $cat2 array('column'=>'NameOfColumn', 'substitute'=>'Substitute') level category, the substitute should be an
     * array which max length should be 3, the first index name will be col1, the second should be col2 and the third one should be short:true/false.
     * it's possible to have only one index: e.g. col1.
     * @param mixed: $indicators array('column'=>'NameOfColumn', 'substitute'=>'Substitute') string array indicators
     * @param mixed: $data
     * @param bool: $sortCategories
     * @return mixed: formatted array
     */
    function chartData2Categories($cat1, $cat2, $indicators, $data, $sortCategories = true) {

        // check if all the parameters are okay
        if(count($data) > 0 && count($cat1)>0 && count($cat2)>0 && count($indicators)>0) {
            // as the data is flat we have to find first the two categories
            $tmp_top_cat = array();
            $tmp_second_cat = array();
            foreach($data as $temp_d) {
                $tmp_top_cat[] = $temp_d[$cat1['column']]; // $cat1/2[0] must be the column name of the categories
                $tmp_second_cat[] = $temp_d[$cat2['column']];
            }

            // we just need the unique categories
            $top_cat = array_unique($tmp_top_cat);
            $second_cat = array_unique($tmp_second_cat);

            // by default the categories will be sorted ASC
            if($sortCategories) {
                sort($top_cat);
                sort($second_cat);
            }

            // the data that should be returned
            $r_data = array();

            // this array will keep the data for each indicator
            $data_indicators = array();

            // temp variable for keeping the category
            $temp_cat = array();
            foreach ($top_cat as $t_c) {
                $sub_cat = array();
                // set the substitute for cat1
                $top_cat_substitute = $t_c;
                foreach ($second_cat as $s_c) {
                    //$sub_cat = array();
                    foreach ($data as $val) {
                        if ($val[$cat1['column']] === $t_c && $val[$cat2['column']] === $s_c) {
                            $top_cat_substitute = array_key_exists('substitute',$cat1) && !is_array($cat1['substitute'])
                                                  ? $val[$cat1['substitute']] : $t_c;

                            $substitue = array_key_exists('substitute', $cat2)?$cat2['substitute']:$s_c;
                            $col1 = is_array($substitue) ? (array_key_exists('col1', $substitue)? $substitue['col1'] : null) : $substitue;
                            $col2 = is_array($substitue) ? (array_key_exists('col2', $substitue)? $substitue['col2'] : null) : null;
                            $short = is_array($substitue) ? (array_key_exists('short', $substitue)? $substitue['short'] : null) : null;

                            // the value of short should be: my (month/year), m(month), y(year)
                            $second_part = $col2!==null?"-".($short=='my'||$short=='y'?$this->shortYear($val[$col2]):$val[$col2]):'';
                            $first_part = ($short=='my'||$short=='m'? $this->shortMonth($val[$col1]):$val[$col1]);
                            $sub_cat[] = $first_part.$second_part;
                                         //
                            foreach($indicators as $key=>$indicator) {
                                $data_indicators[$key][] = $val[$key] == 'null' ? null : (int)$val[$key];
                            }

                        }

                    }
                }
                $temp_cat[] = array('name' => $top_cat_substitute, 'categories' => $sub_cat);
            }
            //$cat['categories'] = $temp_cat;
            $r_data['categories'] = $temp_cat;
//            $data['series'] = array(array('name'=>'Refusal', 'data' => $refusal),
//                array('name'=>'Sleep_NewBorn', 'data'=>$sleep),
//                array('name'=>'Missed', 'data' => $remaining));
            $ser = array();
            foreach ($indicators as $key=>$ind) {
                $ser[] = array('name'=>ucfirst($ind), 'data' => $data_indicators[$key]);
            }

            $r_data['series'] = $ser;

            return $r_data;
        }

        else
            return ['data'=>null];
    }

    /***
     * @param mixed: $cat1 array('column'=>'NameOfColumn', 'substitute'=>'Substitute') top level category, only array of length 2
     * @param mixed: $cat2 array('column'=>'NameOfColumn', 'substitute'=>'Substitute')
     * @param mixed: $cat3 array('column'=>'NameOfColumn', 'substitute'=>'Substitute') level category, the substitute should be an
     * array which max length should be 3, the first index name will be col1, the second should be col2 and the third one should be short:true/false.
     * it's possible to have only one index: e.g. col1.
     * @param mixed: $indicators array('column'=>'NameOfColumn', 'substitute'=>'Substitute') string array indicators
     * @param mixed: $data
     * @param bool: $sortCategories
     * @return mixed: formatted array
     */
    function chartData3Categories($cat1, $cat2, $cat3, $indicators, $data, $sortCategories = true) {

        // check if all the parameters are okay
        if(count($data) > 0 && count($cat1)>0 && count($cat2)>0 && count($indicators)>0 && count($cat3)>0) {
            // as the data is flat we have to find first the two categories
            $tmp_top_cat = array();
            $tmp_second_cat = array();
            $temp_first_cat = array();
            foreach($data as $temp_d) {
                $tmp_top_cat[] = $temp_d[$cat2['column']]; // $cat1/2[0] must be the column name of the categories
                $temp_first_cat[] = $temp_d[$cat1['column']];
                $tmp_second_cat[] = $temp_d[$cat3['column']];
            }

            // we just need the unique categories
            $first_cat = array_unique($temp_first_cat);
            $top_cat = array_unique($tmp_top_cat);
            $second_cat = array_unique($tmp_second_cat);

            // by default the categories will be sorted ASC
            if($sortCategories) {
                sort($first_cat);
                sort($top_cat);
                sort($second_cat);
            }

            // the data that should be returned
            $r_data = array();

            // this array will keep the data for each indicator
            $data_indicators = array();

            // temp variable for keeping the category

            $final_cat = array();
            foreach ($first_cat as $f_c) {
                $temp_cat = array();
                $first_cat_substitute = $f_c;
                foreach ($top_cat as $t_c) {
                    $sub_cat = array();
                    // set the substitute for cat1
                    $top_cat_substitute = $t_c;
                    foreach ($second_cat as $s_c) {
                        //$sub_cat = array();
                        foreach ($data as $val) {
                            if ($val[$cat1['column']] == $f_c && $val[$cat2['column']] === $t_c && $val[$cat3['column']] === $s_c) {
                                $top_cat_substitute = array_key_exists('substitute', $cat2) && !is_array($cat2['substitute'])
                                    ? $val[$cat2['substitute']] : $t_c;

                                $first_cat_substitute = array_key_exists('substitute', $cat1) && !is_array($cat1['substitute'])
                                    ? $val[$cat1['substitute']] : $f_c;

                                $substitue = array_key_exists('substitute', $cat3) ? $cat3['substitute'] : $s_c;
                                $col1 = is_array($substitue) ? (array_key_exists('col1', $substitue) ? $substitue['col1'] : null) : $substitue;
                                $col2 = is_array($substitue) ? (array_key_exists('col2', $substitue) ? $substitue['col2'] : null) : null;
                                $short = is_array($substitue) ? (array_key_exists('short', $substitue) ? $substitue['short'] : null) : null;

                                // the value of short should be: my (month/year), m(month), y(year)
                                $second_part = $col2 !== null ? "-" . ($short == 'my' || $short == 'y' ? $this->shortYear($val[$col2]) : $val[$col2]) : '';
                                $first_part = ($short == 'my' || $short == 'm' ? $this->shortMonth($val[$col1]) : $val[$col1]);
                                $sub_cat[] = $first_part . $second_part;
                                //
                                foreach ($indicators as $key => $indicator) {
                                    $data_indicators[$key][] = $val[$key] == 'null' ? null : (int)$val[$key];
                                }

                            }

                        }
                    }
                    $temp_cat[] = array('name' => $top_cat_substitute, 'categories' => $sub_cat);
                }
                $final_cat[] = array('name'=>$first_cat_substitute, 'categories'=>$temp_cat);
            }
            //$cat['categories'] = $temp_cat;
            $r_data['categories'] = $final_cat;
//            $data['series'] = array(array('name'=>'Refusal', 'data' => $refusal),
//                array('name'=>'Sleep_NewBorn', 'data'=>$sleep),
//                array('name'=>'Missed', 'data' => $remaining));
            $ser = array();
            foreach ($indicators as $key=>$ind) {
                $ser[] = array('name'=>ucfirst($ind), 'data' => $data_indicators[$key]);
            }

            $r_data['series'] = $ser;

            return $r_data;
        }

        else
            return ['data'=>null];
    }

    /***
     * @param mixed: $cat1 array('column'=>'NameOfColumn', 'substitute'=>'Substitute') top level category
     * @param mixed: $indicators array('column'=>'NameOfColumn', 'substitute'=>'Substitute') string array indicators
     * @param mixed: $data
     * @return mixed: formatted array
     */
    function chartData1Category($cat1, $indicators, $data) {

        if(count($data) > 0) {
            $tmp_top_cat = array();
            foreach($data as $temp_d) {
                $tmp_top_cat[] = $temp_d[$cat1];
            }

            $top_cat = array_unique($tmp_top_cat);

            $r_data = array();
            //$cat = array();
            $data_indicators = array();
            $temp_cat = array();
            foreach ($top_cat as $t_c) {


                //$sub_cat = array();
                foreach ($data as $val) {
                    if ($val[$cat1] === $t_c) {

                        foreach($indicators as $indicator) {
                            $data_indicators[$indicator][] = $val[$indicator] == 'null' ? null : (int)$val[$indicator];
                        }

                    }

                }

                $temp_cat[] = $t_c;
            }
            //$cat['categories'] = $temp_cat;
            $r_data['categories'] = $temp_cat;
            $ser = array();
            foreach ($indicators as $key=>$ind) {
                $ser[] = array('name'=>ucfirst($key), 'data' => $data_indicators[$ind]);
            }

            $r_data['series'] = $ser;

            return $r_data;
        }

        else
            return ['data'=>null];
    }


    /***
     * @param $cat1 top level category string name, where together with d_ it should be a column name
     * @param $indicator string array indicators
     * @param $data
     * @return $r_data  formated array
     */
    function pieData1Category($cat1, $indicator, $substitute, $data) {

        if(count($data) > 0) {
            $tmp_top_cat = array();
            foreach($data as $temp_d) {
                $tmp_top_cat[] = $temp_d[$cat1];
            }

            $top_cat = array_unique($tmp_top_cat);

            $r_data = array();
            $data_indicators = array();
            foreach ($top_cat as $t_c) {


                //$sub_cat = array();
                foreach ($data as $val) {
                    if ($val[$cat1] === $t_c) {

                        //foreach($indicators as $indicator) {
                            $data_indicators[] = array('name'=>$t_c, 'y'=>$val[$indicator] == 'null' ? null : (int)$val[$indicator]);
                        //}

                    }

                }

                //$temp_cat[] = $t_c;
            }
            //$cat['categories'] = $temp_cat;
            //$r_data['categories'] = $temp_cat;
            $ser[] = array('type'=>'pie', 'name'=>ucfirst($substitute), 'data' => $data_indicators);

            $r_data['series'] = $ser;

            return $r_data;
        }

        else
            return ['data'=>null];
    }

    /**
     * @param $array
     * @return array
     */

    function clusterDataForTable($array) {
        if(is_array($array)) {
            $month = array();
            foreach ($array as $value) {
                $month[] = $value['d_cMonth'];
            }
            $month = array_unique($month);
            $months = array();
            foreach ($month as $m)
                $months[] = $m;
            unset($month);
            $months = $this->orderMonths($months);
            $data = array();
            $larger_index = array();
            foreach ($months as $month) {
                $c_data = array();
                $d = array();
                foreach ($array as $value) {
                    if($month == $value['d_cMonth']) {
                        $index = ($value['d_subDistrict']!= null || $value['d_subDistrict']!= "")? $value['d_subDistrict']."_".$value['d_cluster']:$value['d_cluster'];
                        $d[$index] = ['absent'=>$value['d_remainingAbsent'],
                            'sleep'=>$value['d_remainingSleep'],
                            'refusal'=>$value['d_remainingRefusal']];
                        $c_data = $d;
                    }
                }
                $larger_index[$month] = count($c_data);
                $data[$month] = $c_data;
            }
            $large_key = array_keys($larger_index, max($larger_index));
            if(is_array($large_key))
                $large_key = $large_key[0];
            $columns_top = array();
            $columns = array(['title'=>'Cluster']);
            foreach($data as $key=>$value) {
                $columns_top[] = $key;
                $columns[] = ['title'=>'Absent'];
                $columns[] = ['title'=>'Sleep'];
                $columns[] = ['title'=>'Refusal'];

            }
            $large = $data[$large_key];
            //die(count($large));
            $rows = array();
            //unset($data[$key]);
            foreach ($large as $k=>$value) {
                $row = array($k);
                $t_sleep = array();
                $t_absent = array();
                $t_refusal = array();
                foreach($data as $d) {
                    //$row = array();
                    if(array_key_exists($k, $d)) {
                        $t_absent[] = $d[$k]['absent'];
                        $row[] = $d[$k]['absent'];
                        $t_sleep[] = $d[$k]['sleep'];
                        $row[] = $d[$k]['sleep'];
                        $t_refusal[] = $d[$k]['refusal'];
                        $row[] = $d[$k]['refusal'];
                        //$row[] = 'chart';
                    } else {
                        $row[] = null;
                        $row[] = null;
                        $row[] = null;
                        $t_absent[] = null;
                        $t_sleep[] = null;
                        $t_refusal[] = null;
                        //$row[] = 'chart';
                    }
                    //$rows[$k] = [$k.",".implode(",", $row)];
                }
                $row[] = "<span class='absent'>".implode(",", $t_absent)."</span> <span class='sleep'>".implode(",", $t_sleep)."</span> <span class='refusal'>".implode(",", $t_refusal)."</span>";
                //$row[] = array('absent'=>$t_absent, 'sleep'=>$t_sleep, 'refusal'=>$t_refusal);
                $rows[] = $row;

            }

            $final_data = array('top_cols'=>$columns_top, 'cols'=>$columns, 'data'=>$rows);
            return $final_data;
            //return $large;


//            $data['larger_key'] = $key;
//            return $data;
        }
        else return ['error'=>'No data'];
    }



}