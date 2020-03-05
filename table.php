<?php
function getJSON($start, $limit)
{
    // Set parameters
    if (!empty($_GET)) {
        $query = urlencode($_GET['query']);
        $city = urlencode($_GET['city']);
        $radius = '25';
        $days = '30';
        $country = urlencode($_GET['country']);

        if (!empty($query)) {
            $query = urlencode('title:' . $_GET['query']);
        }

        if (!empty($query) && isset($_GET['junior'])) {
            $query .= urlencode(' and ');
        }

        if (isset($_GET['junior'])) {
            $query .= urlencode('title:(grad or college or university or entry or junior or jr or campus or intern or internship or coop or associate or student or trainee)');
        }

        $direct = isset($_GET['direct']) ? 'employer' : '';

        $url = 'http://api.indeed.com/ads/apisearch?publisher=628092503915884&v=2&format=json&sort=date&q=' . $query .
            '&l=' . $city . '&radius=' . $radius . '&start=' . $start . '&limit=' . $limit . '&fromage=' . $days . '&st=' . $direct . '&co=' . $country .
            '&userip=1.2.3.4&useragent=Mozilla/%2F4.0%28Firefox%29';

        // Get JSON from URL
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $response = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($response, true);

        return $json;
    }
}
function getTotalJobs()
{
    // Count the number of jobs
    $json = getJSON(1, 1);
    return $json['totalResults'];
}

function addPages($a, $b)
{
    global $page;

    unset($_GET['page']);
    $queryString = $_SERVER['PHP_SELF'] . '?' . http_build_query($_GET) . '&page=';

    for ($i = $a; $i <= $b; $i++) {
        if ($i == $page) {
            echo '<li class="active"><a href="' . $queryString . $i . '">' . $i . '</a></li>';
        } else {
            echo '<li><a href="' . $queryString . $i . '">' . $i . '</a></li>';
        }
    }
}

function timeAgo($time)
{
    // Convert time string to relative time (time ago)
    $diff = time() - strtotime($time);

    if ($diff < 1) {
        return 'Just now';
    }

    $timeRules = array(
        365 * 24 * 60 * 60 => 'year',
        30 * 24 * 60 * 60 => 'month',
        24 * 60 * 60 => 'day',
        60 * 60 => 'hour',
        60 => 'minute',
        1 => 'second'
    );

    foreach ($timeRules as $secs => $str) {
        $div = $diff / $secs;

        if ($div >= 1) {
            $t = round($div);

            return $t . ' ' . $str . ($t > 1 ? 's' : '') . ' ago';
        }
    }
}

$totalJobs = getTotalJobs();
$jobsPerPage = 20;
$limit = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $jobsPerPage;
$totalPages = ceil($totalJobs / $jobsPerPage);

if (!empty($_GET)) {
    echo '<div class="results">';
    echo '<h3>Number of jobs: ' . $totalJobs . '</h3>';
    echo '<table id="results" class="table table-condensed table-hover">';
    echo '<thead><tr><th>COMPANY</th><th>LOCATION</th><th>POSTED</th><th>TITLE</th></tr></thead><tbody>';

    for ($i = $start; $i < $jobsPerPage * $page; $i += $limit) {
        if ($i > $totalJobs) {
            break;
        }

        $json = getJSON($i, $limit);

        // Parse data from JSON
        foreach ($json['results'] as $job) {
            $company = ($job['company'] == "") ? "N/A" : $job['company'];
            $location = $job['formattedLocation'];
            $title = $job['jobtitle'];
            $link = $job['jobkey'];
            $time = $job['date'];

            // Output data in HTML tables
            echo '<tr>';
            echo '<td width="25%">' . $company . '</td>';
            echo '<td width="15%">' . $location . '</td>';
            echo '<td width="10%" data-order="' . strtotime($time) . '">' . timeAgo($time) . '</td>';
            echo '<td width="50%"><a href="https://www.indeed.com/rc/clk?jk=' . $link . '">' . $title . '</a></td>';
            echo '</tr>';
        }
    }
    echo '</tbody></table>';

    unset($_GET['page']);
    $queryString = $_SERVER['PHP_SELF'] . '?' . http_build_query($_GET) . '&page=';

    echo '<ul class="pagination">';

    if ($totalPages <= 5) {
        echo ($page > 1) ? '<li><a href="' . $queryString . ($page - 1) . '">&lt; Prev</a>' : '';
        addPages(1, $totalPages);
    } else if ($page > 0 and $page <= $totalPages - 3) {
        echo ($page > 1) ? '<li><a href="' . $queryString . ($page - 1) . '">&lt; Prev</a>' : '';
        echo '<li class="active"><a href="' . $queryString . $page . '">' . $page . '</a></li>';
        addPages($page + 1, $page + 2);
        echo '<li><span class="disabled">...</span></li>';
        addPages($totalPages, $totalPages);
    } else if ($page > $totalPages - 3) {
        echo '<li><a href="' . $queryString . ($page - 1) . '">&lt; Prev</a>';
        echo '<li><a href="' . $queryString . 1 . '">1</a></li>';
        echo '<li><span class="disabled">...</span></li>';
        addPages($totalPages - 2, $totalPages);
    }
    echo ($page < $totalPages) ? '<li><a href="' . $queryString . ($page + 1) . '">Next &gt;</a>' : '';
    echo '</ul>';
    echo '</div>';
    echo '<span id="indeed_at"><a title="Job Search" href="https://www.indeed.com" rel="nofollow" >jobs by <img alt=Indeed src="https://www.indeed.com/p/jobsearch.gif" style="border: 0; vertical-align: middle;"></a></span>';
}