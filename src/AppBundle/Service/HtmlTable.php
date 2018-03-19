<?php
/**
 * Created by PhpStorm.
 * User: wakhan
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
            $coverage = number_format((($totalVac/$target) * 100),
                2, '.', ',');

            $progress = "<div class=\"progress progress-sm\"
                            style=\"background-color: #cb4b16\">
                             <div class=\"progress-bar progress-bar-success\" 
                                  style=\"width:".$coverage."%\">
                             </div>
                         </div>";
            $tr .= "<td title=\"Coverage: ".$coverage."%\">".$progress."</td>";
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
    public static function tableForAdminData($data, $type = "Region") {
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
            $coverage = number_format((($totalVac/$target) * 100),
                2, '.', ',');

            $progress = "<div class=\"progress progress-sm\"
                            style=\"background-color: #cb4b16\">
                             <div class=\"progress-bar progress-bar-success\" 
                            style=\"width:".$coverage."%\">
                             </div>
                         </div>";
            $tr .= "<td title=\"Coverage: ".$coverage."%\">".$progress."</td>";
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
            $coverage = number_format((($totalVac/$target) * 100),
                2, '.', ',');

            $progress = "<div class=\"progress progress-sm\"
                            style=\"background-color: #cb8a0c\">
                             <div class=\"progress-bar progress-bar-success\" 
                            style=\"width:".$coverage."%\">
                             </div>
                         </div>";
            $tr .= "<td title=\"Recovered: ".$coverage."%\">".$progress."</td>";
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
                                            ".number_format($data[0]['VacWastage'],2, '.', ',')."
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
                                            ".number_format($data[0]['VacWastage'],2, '.', ',')."
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
     * @return string
     */
    public static function tableODK($data, $headerVars) {
        $table = "<table id='tbl-odk-data' class=\"table table-bordered table-striped table-responsive\">";
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
                    $color = self::numToColor($datum[$index], 0, 1);
                } else if($calc === 'rev') {
                    $color = self::numToColor($datum[$index], 0, 1);
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

    public static function hslToRgb($h, $s, $l){
        $r = $b = $g = null;
        if($s == 0) {
            $r = $g = $b = $l; // achromatic
        } else {
            $q = $l < 0.5 ? $l * (1 + $s) : $l + $s - $l * $s;
            $p = 2 * $l - $q;
            $r = self::hue2rgb($p, $q, $h + 1/3);
            $g = self::hue2rgb($p, $q, $h);
            $b = self::hue2rgb($p, $q, $h - 1/3);
        }

        return [floor($r * 255), floor($g * 255), floor($b * 255)];
    }

    public static function numToColor($i, $min, $max) {
        $ratio = $i;
        if ($min> 0 || $max < 1) {
            if ($i < $min) {
                $ratio = 0;
            } else if ($i > $max) {
                $ratio = 1;
            } else {
                $range = $max - $min;
                $ratio = ($i-$min) / $range;
            }
        }

        // as the function expects a value between 0 and 1, and red = 0° and green = 120°
        // we convert the input to the appropriate hue value
        $hue = $ratio * 1.2 / 3.60;

        // we convert hsl to rgb (saturation 100%, lightness 50%)
        $rgb = self::hslToRgb($hue, 1, .5);
        // we format to css value and return
        return 'rgb('.$rgb[0].','.$rgb[1].','.$rgb[2].')';
    }

    public static function hue2rgb($p, $q, $t){
        if($t < 0) $t += 1;
        if($t > 1) $t -= 1;
        if($t < 1/6) return $p + ($q - $p) * 6 * $t;
        if($t < 1/2) return $q;
        if($t < 2/3) return $p + ($q - $p) * (2/3 - $t) * 6;
        return $p;
    }


}