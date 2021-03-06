<?php
/**
 * Acuparse - AcuRite Access/smartHUB and IP Camera Data Processing, Display, and Upload.
 * @copyright Copyright (C) 2015-2021 Maxwell Power
 * @author Maxwell Power <max@acuparse.com>
 * @link http://www.acuparse.com
 * @license AGPL-3.0+
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this code. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * File: src/pub/api/v1/json/tower/index.php
 * Get weather JSON data
 */

// Get the loader
require(dirname(dirname(dirname(dirname(dirname(__DIR__))))) . '/inc/loader.php');

/** @var mysqli $conn Global MYSQL Connection */
/**
 * @return array
 * @var object $config Global Config
 */

header('Content-Type: application/json; charset=UTF-8'); // Set the header for JSON output

function getTowerLightningData()
{
    require(APP_BASE_PATH . '/fcn/weather/getCurrentTowerLightningData.php');
    $getTowerLightningData = new tower\getCurrentLightningData;
    $jsonExportTowerLightning = array("towerLightning" => $getTowerLightningData->getJSONData());
    $result = $jsonExportTowerLightning;

    if (empty($result)) {
        header($_SERVER["SERVER_PROTOCOL"] . " 500 Internal Server Error");
        echo json_encode(['Error' => "Lightning Data Unavailable"]);
    } else {
        return json_encode($result);
    }
}

// Access Token
include(APP_BASE_PATH . '/fcn/api/auth/getToken.php');

if (isset($_GET['lightning'])) {
    if ($config->station->device === 0) {
        if ($config->station->primary_sensor === 0) {
            if ($config->station->lightning_source === 2 || $config->station->lightning_source === 3) {
                getTowerLightningData();
            }
        } else if ($config->station->primary_sensor === 1) {
            if ($config->station->lightning_source === 2) {
                getTowerLightningData();
            }
        }
    } else {
        header($_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
        echo json_encode(array("message" => "Bad Request - Tower lightning not enabled"));
    }
} elseif ($config->station->towers === true) {
    if (isset($_GET['id'])) {
        $sensor = sprintf('%08d', filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT));

        if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
            $result = mysqli_query($conn, "SELECT * FROM `towers` WHERE `sensor` = '$sensor' ORDER BY `arrange`");
        } else {
            $result = mysqli_query($conn,
                "SELECT * FROM `towers` WHERE `private` = 0 AND `sensor` = '$sensor' ORDER BY `arrange`");
        }
        $towerCount = mysqli_num_rows($result);

        if ($towerCount >= 1) {
            $initString = '{"towers":{';
            include(APP_BASE_PATH . '/fcn/api/json/getTowers.php');
            echo "}}";
        } else {
            header($_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
            echo json_encode(["message" => "Bad Request - Invalid Tower ID or Unauthorized"]);
        }
    } else {
        // Can we display private data?
        if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
            $result = mysqli_query($conn, "SELECT * FROM `towers` ORDER BY `arrange`");
        } else {
            $result = mysqli_query($conn, "SELECT * FROM `towers` WHERE `private` = 0 ORDER BY `arrange`");
        }
        $towerCount = mysqli_num_rows($result);
        if ($towerCount >= 1) {
            $initString = '{"towers":{';
            include(APP_BASE_PATH . '/fcn/api/json/getTowers.php');
        }
        echo "}}";
    }
} else {
    header($_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
    echo json_encode(["message" => "Bad Request"]);
}

// End Token
include(APP_BASE_PATH . '/fcn/api/auth/endToken.php');
