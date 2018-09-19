<?php
/**
 * Created by PhpStorm.
 * User: Wazir Khan Ahmadzai
 * Date: 2/18/2018
 * Time: 11:09 AM
 */

namespace AppBundle\Service;


class HtmlTable
{

    /**
     * @param $data
     * @param string $type
     * @return string (html table)
     */
    public static function tableForDashboard($data, $type = "Region") {
        if(count($data) === 0 || $data === null)
            return self::noDataTable();
        $table = " <table class=\"table table-bordered\">";
        $th = "<tr>
                 <th>".$type."</th>
                 <th title='Calculated Target, Source Coverage Data'>Target</th>
                 <th>Vac Campaign</th>
                 <th title='After Catchup'>Vac Catchup</th>
                 <th>Missed</th>
                 <th style='color: orange'>Absent</th>
                 <th style='color: saddlebrown;'>NSS</th>
                 <th style='color: red'>Refusal</th>
                 <th style='color: dimgrey' title='Discrepancy between Tallysheet and Register Data'>
                    Discrepancy
                 </th>
              </tr>";

        $rows = "";
        foreach($data as $datum) {
            $tr = "<tr>";
            $tr .= "<td>".$datum[$type]."</td>";
            $target = (int) $datum['TotalRemaining'] + (int) $datum['TotalVac'];
            $tr .= "<td>".number_format($target, 0, '.', ',')."</td>";

            $totalVac = (int) $datum['TotalVac'];
            $totalVac = number_format(($totalVac),
                0, '.', ',');

            $tr .= "<td>".$totalVac."</td>";
            $cTotalVac = $datum['cTotalVac'] == 0 ? '':
                         number_format($datum['cTotalVac'], 0, '.', ',');
            $tr .= "<td>".$cTotalVac."</td>";
            $tr .= "<td>".number_format($datum['DiscRemaining'], 0, '.', ',')."</td>";
            $tr .= "<td>".number_format($datum['DiscRemainingAbsent'], 0, '.', ',')."</td>";
            $tr .= "<td>".number_format($datum['DiscRemainingNSS'], 0, '.', ',')."</td>";
            $tr .= "<td>".number_format($datum['DiscRemainingRefusal'], 0, '.', ',')."</td>";
            $disc = $datum['Disc'] == 0 && $cTotalVac == 0 ? '':
                    number_format($datum['Disc'], 0, '.', ',');
            $tr .= "<td>".$disc."</td>";

            $tr .= "</tr>";

            $rows .= $tr;
        }

        return $table.$th.$rows."</table>";
    }

    /**
     * @param $data
     * @param string $type
     * @return string (html table)
     */
    public static function tableForAdminData($data, $type = "Region") {
        if(count($data) === 0 || $data === null)
            return self::noDataTable();
        $table = " <table class=\"table table-bordered\">";
        $th = "<tr>
                 <th>".$type."</th>
                 <th>U5 Children</th>
                 <th>Coverage %</th>
                 <th>Missed</th>
                 <th style='color: orange'>Absent</th>
                 <th style='color: saddlebrown;'>NSS</th>
                 <th style='color: red'>Refusal</th>
              </tr>";

        $rows = "";
        foreach($data as $datum) {
            $tr = "<tr>";
            $tr .= "<td>".$datum[$type]."</td>";
            $target = (int) $datum['TotalRemaining'] + (int) $datum['TotalVac'];
            $tr .= "<td>".number_format($target, 0, '.', ',')."</td>";

            $totalVac = (int) $datum['TotalVac'];
            $coverage = $target == 0 ? 0: number_format((($totalVac/$target) * 100),
                2, '.', ',');

            $rem = 100 - $coverage;
            $progress = "<div class=\"progress progress-sm\" title='Remaining $rem%'
                            style=\"background-color: #FFB32D\">
                             <div class=\"progress-bar\" title=\"Coverage: ".$coverage."%\"
                            style=\"background-color:#08beff; width:".$coverage."%\">
                             </div>
                         </div>";
            $tr .= "<td>".$progress."</td>";
            $tr .= "<td>".number_format($datum['TotalRemaining'], 0, '.', ',')."</td>";
            $tr .= "<td>".number_format($datum['RemAbsent'], 0, '.', ',')."</td>";
            $tr .= "<td>".number_format($datum['RemNSS'], 0, '.', ',')."</td>";
            $tr .= "<td>".number_format($datum['RemRefusal'], 0, '.', ',')."</td>";

            $tr .= "</tr>";

            $rows .= $tr;
        }

        return $table.$th.$rows."</table>";
    }

    /**
     * @param $data
     * @param string $type
     * @return string (html table)
     */
    public static function tableForCatchupData($data, $type = "Region") {
        if(count($data) === 0 || $data === null)
            return self::noDataTable();
        $table = " <table class=\"table table-bordered\">";
        $th = "<tr>
                 <th>".$type."</th>
                 <th>Target Missed</th>
                 <th>Recovered %</th>
                 <th>Still Missed</th>
                 <th style='color: orange'>Absent</th>
                 <th style='color: saddlebrown;'>NSS</th>
                 <th style='color: red'>Refusal</th>
              </tr>";

        $rows = "";
        foreach($data as $datum) {
            $tr = "<tr>";
            $tr .= "<td>".$datum[$type]."</td>";
            $target = (int) $datum['RegMissed'];
            $tr .= "<td>".number_format($target, 0, '.', ',')."</td>";

            $totalVac = (int) $datum['TotalRecovered'];
            $coverage = $target == 0? 0: number_format((($totalVac/$target) * 100),
                2, '.', ',');

            $rem = 100 - $coverage;
            $progress = "<div class=\"progress progress-sm\" title='Remaining $rem%'
                            style=\"background-color: #FFB32D\">
                             <div class=\"progress-bar\" title=\"Recovered: ".$coverage."%\"
                            style=\"background-color:#40C97A; width:".$coverage."%\">
                             </div>
                         </div>";
            $tr .= "<td>".$progress."</td>";
            $tr .= "<td>".number_format($datum['TotalRemaining'], 0, '.', ',')."</td>";
            $tr .= "<td>".number_format($datum['RemAbsent'], 0, '.', ',')."</td>";
            $tr .= "<td>".number_format($datum['RemNSS'], 0, '.', ',')."</td>";
            $tr .= "<td>".number_format($datum['RemRefusal'], 0, '.', ',')."</td>";

            $tr .= "</tr>";

            $rows .= $tr;
        }

        return $table.$th.$rows."</table>";
    }

    /**
     * @param $data
     * @return string (html)
     */
    public static function infoForDashboard($data) {
        if(count($data) === 0 || $data === null)
            return "";
        $row = "<div class=\"col-md-3 col-sm-6 col-xs-12\">
                                <div class=\"c-info-box\">
                                    <span class=\"c-info-box-icon bg-green\"><i class=\"fa fa-child\"></i></span>

                                    <div class=\"c-info-box-content\">
                                        <span class=\"c-info-box-text\">Total Vaccinated</span>
                                        <span class=\"c-info-box-number info-vaccinated-child\">
                                            ". number_format($data[0]['FinalTotalVac'],0, '.', ',') ."
                                        </span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                            <div class=\"col-md-3 col-sm-6 col-xs-12\">
                                <div class=\"c-info-box\">
                                    <span class=\"c-info-box-icon bg-aqua\"><i class=\"fa fa-child\"></i></span>

                                    <div class=\"c-info-box-content\">
                                        <span class=\"c-info-box-text\">Vac Campaign</span>
                                        <span class=\"c-info-box-number info-missed-child\">
                                            ". number_format($data[0]['TotalVac'],0, '.', ',')."
                                        </span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                            <div class=\"col-md-3 col-sm-6 col-xs-12\">
                                <div class=\"c-info-box\">
                                    <span class=\"c-info-box-icon bg-aqua-active\"><i class=\"fa fa-child\"></i></span>

                                    <div class=\"c-info-box-content\">
                                        <span class=\"c-info-box-text\">Vac Catchup</span>
                                        <span class=\"c-info-box-number info-used-vials\">
                                            ".number_format($data[0]['cTotalVac'],0, '.', ',')."
                                        </span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                            <div class=\"col-md-3 col-sm-6 col-xs-12\">
                                <div class=\"c-info-box\">
                                    <span class=\"c-info-box-icon bg-red\"><i class=\"fa fa-child\"></i></span>

                                    <div class=\"c-info-box-content\">
                                        <span class=\"c-info-box-text\">Remaining</span>
                                        <span class=\"c-info-box-number info-coverage\">
                                            ".number_format($data[0]['DiscRemaining'],0, '.', ',')."
                                        </span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>";
        return$row;
    }
    /**
     * @param $data
     * @return string (html)
     */
    public static function infoForAdminData($data) {
        if(count($data) === 0 || $data === null)
            return "";
        $row = "<div class=\"col-md-3 col-sm-6 col-xs-12\">
                                <div class=\"c-info-box\">
                                    <span class=\"c-info-box-icon bg-aqua\"><i class=\"fa fa-child\"></i></span>

                                    <div class=\"c-info-box-content\">
                                        <span class=\"c-info-box-text\">Child Vaccinated</span>
                                        <span class=\"c-info-box-number info-vaccinated-child\">
                                            ". number_format($data[0]['TotalVac'],0, '.', ',') ."
                                        </span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                            <div class=\"col-md-3 col-sm-6 col-xs-12\">
                                <div class=\"c-info-box\">
                                    <span class=\"c-info-box-icon bg-yellow\"><i class=\"fa fa-warning\"></i></span>

                                    <div class=\"c-info-box-content\">
                                        <span class=\"c-info-box-text\">Missed</span>
                                        <span class=\"c-info-box-number info-missed-child\">
                                            ". number_format($data[0]['TotalRemaining'],0, '.', ',')."
                                        </span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                            <div class=\"col-md-3 col-sm-6 col-xs-12\">
                                <div class=\"c-info-box\">
                                    <span class=\"c-info-box-icon bg-maroon\"><i class=\"fa fa-eyedropper\"></i></span>

                                    <div class=\"c-info-box-content\">
                                        <span class=\"c-info-box-text\">Used Vials</span>
                                        <span class=\"c-info-box-number info-used-vials\">
                                            ".number_format($data[0]['UVials'],0, '.', ',')."
                                        </span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                            <div class=\"col-md-3 col-sm-6 col-xs-12\">
                                <div class=\"c-info-box\">
                                    <span class=\"c-info-box-icon bg-orange\"><i class=\"fa fa-line-chart\"></i></span>

                                    <div class=\"c-info-box-content\">
                                        <span class=\"c-info-box-text\">Vac Wastage</span>
                                        <span class=\"c-info-box-number info-coverage\">
                                            ".number_format($data[0]['VacWastage'],2, '.', ',')."%
                                        </span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>";
        return$row;
    }

    /**
     * @param $data
     * @return string (html)
     */
    public static function infoForCatchup($data) {
        if(count($data) === 0 || $data === null)
            return "";
        $row = "<div class=\"col-md-3 col-sm-6 col-xs-12\">
                                <div class=\"c-info-box\">
                                    <span class=\"c-info-box-icon bg-yellow\"><i class=\"fa fa-child\"></i></span>

                                    <div class=\"c-info-box-content\">
                                        <span class=\"c-info-box-text\">Target</span>
                                        <span class=\"c-info-box-number info-vaccinated-child\">
                                            ".number_format($data[0]['RegMissed'], 0, '.', ',') ."
                                        </span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                            <div class=\"col-md-3 col-sm-6 col-xs-12\">
                                <div class=\"c-info-box\">
                                    <span class=\"c-info-box-icon bg-aqua\"><i class=\"fa fa-child\"></i></span>

                                    <div class=\"c-info-box-content\">
                                        <span class=\"c-info-box-text\">Recovered</span>
                                        <span class=\"c-info-box-number info-missed-child\">
                                            ". number_format($data[0]['TotalRecovered'],0, '.', ',') ."
                                        </span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                            <div class=\"col-md-3 col-sm-6 col-xs-12\">
                                <div class=\"c-info-box\">
                                    <span class=\"c-info-box-icon bg-info\"><i class=\"fa fa-child\"></i></span>

                                    <div class=\"c-info-box-content\">
                                        <span class=\"c-info-box-text\">Unrecorded</span>
                                        <span class=\"c-info-box-number info-used-vials\">
                                            ". number_format($data[0]['VacUnRecorded'],0, '.', ',') ."
                                        </span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                            <div class=\"col-md-3 col-sm-6 col-xs-12\">
                                <div class=\"c-info-box\">
                                    <span class=\"c-info-box-icon bg-red\"><i class=\"fa fa-child\"></i></span>

                                    <div class=\"c-info-box-content\">
                                        <span class=\"c-info-box-text\">Remaining</span>
                                        <span class=\"c-info-box-number info-coverage\">
                                            ". number_format($data[0]['TotalRemaining'],0, '.', ',') ."
                                        </span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>";
        return$row;
    }


    /**
     * @param $data
     * @param $headerVars (col, label, calc)
     * @param $min (min color (0-1))
     * @param $max (max color (0-1))
     * @return string
     */
    public static function tableODK($data, $headerVars, $min = null, $max = null) {
        $table = "<table id='tbl-odk-data' class=\"table table-bordered table-striped 
                                                   table-responsive\" 
                         style=\"width:100%\" '>";
        $th = "<thead>";
        foreach ($headerVars as $var) {
            $header = array_key_exists('label', $var) ? $var['label'] : ucfirst($var);
            $th .= "<th>" . $header . "</th>";
        }
        $th .="</thead>";

        $rows = "<tbody>";
        foreach($data as $datum) {
            $tr = "<tr>";
            foreach ($headerVars as $headerVar) {
                $index = array_key_exists('col', $headerVar) ? $headerVar['col'] : $headerVar;
                $calc = array_key_exists('calc', $headerVar) ? $headerVar['calc'] : 'normal';
                $value = is_numeric($datum[$index]) ? round($datum[$index]*100, 0):$datum[$index];
                $color = '#CCCCCC';
                $finalValue = is_numeric($value) ? $value.'%': $value;

                if($calc === 'normal') {
                    $color = ColorMgr::numToColor($datum[$index], $min===null?0:$min, $max===null?1:$max);
                } else if($calc === 'rev') {
                    $colorValue = abs((1-$datum[$index]));
                    $color = ColorMgr::numToColor($colorValue, $min===null?0.5:$min, $max===null?1:$max);
                } else if($calc === 'none') {
                    $value = is_numeric($value) ? round($value/100, 0): $value;
                    $finalValue = $value;
                }

                $align = is_numeric($value) ? 'text-align:center' : '';
                $color = $value === null ? '#CCCCCC' : $color;

                $tr .= "<td style=\"background-color: $color; $align\">" . $finalValue . "</td>";
            }
            $tr .= "</tr>";
            $rows .= $tr;
        }

        $rows .= "</tbody>";

        return $table.$th.$rows."</table>";
    }

    /**
     * @param $data
     * @param $headerVars (col, label, calc)
     * @param $min (min color (0-1))
     * @param $max (max color (0-1))
     * @param $type (number type (number|percent))
     * @param $title (null|text)
     * @return string
     */
    public static function heatMapTable($data, $headerVars, $title = null,
                                        $min = null, $max = null, $type='normal') {

        $table = "<div>
                   <table id='tbl-data' class=\"table table-bordered table-striped table-responsive less-padding\" 
                         style=\"width:100%\" '>";
        if($title !== null) {
            $table .= "<caption>".$title."</caption>";
        }

        $th = "<thead>";
        foreach ($headerVars as $var) {
            $header = array_key_exists('label', $var) ? $var['label'] : ucfirst($var);
            $th .= "<th>" . $header . "</th>";
        }
        $th .="</thead>";

        $rows = "<tbody>";
        foreach($data as $datum) {
            $tr = "<tr>";
            foreach ($headerVars as $headerVar) {
                $index = array_key_exists('col', $headerVar) ? $headerVar['col'] : $headerVar;
                $calc = array_key_exists('calc', $headerVar) ? $headerVar['calc'] : 'normal';
                $value = $datum[$index];
                $color = '#CCCCCC';
                $finalValue = is_numeric($value) && $type == 'percent'  ? $value.'%': $value;
                if($calc === 'normal') {
                    $color = ColorMgr::numberToColor($datum[$index], $min===null?20:$min, $max===null?50:$max);
                } else if($calc === 'rev') {
                    $color = ColorMgr::numberToColor($datum[$index], $min===null?20:$min,
                        $max===null?50:$max, null, true);
                } else if($calc === 'none') {
                    $value = $datum[$index];
                    $finalValue = $value;
                }

                $align = is_numeric($value) ? 'text-align:center' : '';
                $color = $value === null ? '#CCCCCC' : $color;

                $tr .= "<td style=\"background-color: $color; $align\">" . $finalValue . "</td>";
            }
            $tr .= "</tr>";
            $rows .= $tr;
        }

        $rows .= "</tbody>";

        $info = "<div class='col-sm-12' style='padding-left:0'><a name='benchmark'>BenchMark:</a> <span class='btn btn-sm' 
                     style='background-color:".
                     ColorMgr::numberToColor($min, $min, $max, null, true)."'>Min: $min</span> 
                     <span class='btn btn-sm' 
                     style='background-color:".
            ColorMgr::numberToColor($max, $min, $max, null, true)."'>Max: $max</span>
                     </div>";
        return $table.$th.$rows."</table></div>".$info;
    }

    /**
     * @param $indicator
     * @return string
     */
    public static function heatMapTableHeader($indicator) {

        $title = "";
        switch ($indicator) {
            case "RemAbsent":
                $title = "Trends of Remaining Absent Children";
                break;
            case "RemNSS":
                $title = "Trends of Remaining NSS Children";
                break;
            case "RemRefusal":
                $title = "Trends of Remaining Refusal Children";
                break;
            case "TotalRemaining":
                $title = "Trends of Total Remaining Children (all reasons)";
                break;
            case "RemAbsentPer":
                $title = "Trends of Remaining Absent Children in Percentage";
                break;
            case "RemNSSPer":
                $title = "Trends of Remaining NSS Children in Percentage";
                break;
            case "RemRefusalPer":
                $title = "Trends of Remaining Refusal Children in Percentage";
                break;
            case "TotalRemainingPer":
                $title = "Trends of Total Remaining Children (all reasons) in Percentage";
                break;
        }

        return $title;
    }


    private function noDataTable() {
        return "<table><tr><td>No Data for the selected filters</td></tr></table>";
    }

}