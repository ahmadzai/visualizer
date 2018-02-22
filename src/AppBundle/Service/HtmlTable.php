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

}