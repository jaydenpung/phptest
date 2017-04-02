#!/usr/bin/php -q
<?php

/**
* Setup:
* sudo apt install php7.0-cli
* sudo apt-get install php-curl
*
* Run:
* php show_api.php "state=johor"
* php show_api.php "area=miri"
*/

    try {
        run();
    }
    catch (Exception $e) {
        echo "Error: " .$e->getMessage();
    }

    // Main function
    function run() {
        try {
            // Check if argument is passed
            if ($_SERVER["argc"] != 2) {
                throw new Exception("Please enter an argument (eg. show_api.php \"state=johor\")\n");
            };

            $serverArgv = str_replace(' ', '',$_SERVER["argv"][1]);
            $arguments = explode("=", $serverArgv);

            // Check if argument is valid
            if (sizeof($arguments) != 2) {
                throw new Exception("Invalid argument. Please enter a valid argument (eg. show_api.php \"state=johor\")\n");
            };

            // Generate link and retrieve data
            $data = generateLinkAndGetData();

            $option = strtolower($arguments[0]);
            $param = strtolower($arguments[1]);

            // Search for data based on state or area and print result
            search($option, $param, $data);

            return true;
        }
        catch (Exception $e) {
            echo "Error in run(): " .$e->getMessage();
            return false;
        }
    }

    // Function to generate link and retrieve latest data
    function generateLinkAndGetData() {
        try {
            // Get the hour zone and date
            $date = date("Y-m-d");
            $hour = idate("H");

            if ($hour >= 0 && $hour <= 5) {
                $hour = 1;
            }
            else if ($hour >= 6 && $hour <= 11) {
                $hour = 2;
            }
            else if ($hour >= 12 && $hour <= 17) {
                $hour = 3;
            }
            else if ($hour >= 18 && $hour <= 24) {
                $hour = 4;
            }
            else {
                throw new Exception("Something wrong with the date.\n");
            }

            // If no data returned, try with previous (hour zone / date) page, will not go beyond yesterday
            while (empty($data)) {
                $link = "http://apims.doe.gov.my/v2/hour{$hour}_{$date}.html";
                echo "Retrieving from $link\n";

                // Try to retrieve data
                $data = retrieveData($link);

                // Set revious hour zone or date if no data retrieved
                if ($hour > 1) {
                    $hour--;
                }
                else if ($date == date("Y-m-d")) {
                    $hour = 4;
                    $date = date("Y-m-d",strtotime("-1 day"));
                }
                else {
                    // Throw error if still no data found after looking at all pages of yesterday
                    $curl = curl_init();
                    curl_setopt ($curl, CURLOPT_URL, $link);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl,CURLOPT_FAILONERROR,true);
                    $page = curl_exec($curl);

                    if(curl_error($curl)) {
                        throw new Exception ("Curl Error: " . curl_error($curl));
                    }
                }
            }

            return $data;
        }
        catch (Exception $e) {
            echo "Error in generateLink(): " .$e->getMessage();
            throw $e;
        }
    }

    // Function to retrieve data on given link
    function retrieveData($link) {
        try {
            // Get the page
            $curl = curl_init();
            curl_setopt ($curl, CURLOPT_URL, $link);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl,CURLOPT_FAILONERROR,true);
            $page = curl_exec($curl);

            if(curl_error($curl)) {
                curl_close ($curl);
                return;
            }

            curl_close ($curl);

            if (!$page) {
                throw new Exception("Unable to reach $link");
            }

            // Get all rows except header row
            preg_match_all('#<tr>(.*?)</tr>#is', $page, $matches);
            $rows = $matches[0];

            // Build data map
            $data = array();
            foreach ($rows as &$row) {
                preg_match_all('#<td(.*?)</td>#is', $row, $matches);
                $columns = $matches[0];

                // Get state and area
                $state = strtolower(preg_split("#<\/?td(.*?)>#is", $columns[0])[1]);
                $area = strtolower(preg_split("#<\/?td(.*?)>#is", $columns[1])[1]);

                // Get latest value
                $value = "";
                preg_match_all('/<b>\d+(.*?)<\/b>/', $row, $columnMatches);

                foreach ($columnMatches[0] as &$column) {
                    // last column with digit value will be the latest value
                    $value = preg_split("/<\/?b>/", $column)[1];
                }

                // Get value
                if ($value) {
                    $data[$state][$area] = $value;
                }
            }

            return $data;
        }
        catch (Exception $e) {
            echo "Error in retrieveData(): " .$e->getMessage();
            throw $e;
        }
    }

    // Function to search for required information in retrieved data
    function search($option, $param, $data) {
        try {
            echo "===================== RESULTS =====================\n\n";

            $areas = array();
            if ($option == "state") {
                echo "State: $param\n\n";
                $areas = $data[$param];
                foreach ($areas as $area => $value) {
                    echo "$area: $value\n";
                }
            }
            elseif ($option == "area") {
                foreach ($data as &$state) {
                    if (array_key_exists($param, $state)) {
                        $value = $state[$param];
                        echo "$param: $value\n";
                    }
                }
            }
            else {
                throw new Exception("Invalid argument. Please use state or area (eg. show_api.php \"area=miri\")\n");
            }
        }
        catch (Exception $e) {
            echo "Error in search(): " .$e->getMessage();
            throw $e;
        }
    }
?>