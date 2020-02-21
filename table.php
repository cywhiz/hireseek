<?php
function getJSON($start, $limit) {
    
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
            $query.= urlencode(' and ');
        }
        
        if (isset($_GET['junior'])) {
            $query.= urlencode('title:(grad or college or university or entry or junior or jr or campus or intern or or internship or coop or associate or student or trainee)');
        }

        $direct = isset($_GET['direct']) ? 'employer' : '';
           
        $url = 'http://api.indeed.com/ads/apisearch?publisher=628092503915884&v=2&format=json&q=' . $query . '&l=' . $city . '&sort=date&radius=' . $radius . '&start=' . $start . '&limit=' . $limit . '&fromage=' . $days. '&st=' . $direct. '&co=' . $country . '&userip=1.2.3.4&useragent=Mozilla/%2F4.0%28Firefox%29';
        
        // Get JSON from URL
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($response, true);
        return $json;
    }
}
function getTotalJobs() {
    
    // Count the number of jobs
    $json = getJSON(1, 1);
    return $json['totalResults'];
}

function addPages($a, $b) {
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

$totalJobs = getTotalJobs();
$jobsPerPage = 50;
$limit = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $jobsPerPage;
$totalPages = ceil($totalJobs / $jobsPerPage);

if (!empty($_GET)) {
    echo '<div class="results">';
    echo '<h3>Number of jobs: ' . $totalJobs. '</h3>';
    echo '<table id="results" class="table table-condensed table-hover">';
    echo '<thead><tr bgcolor=#CCCCCC><th>COMPANY</th><th>LOCATION</th><th>POSTED</th><th>TITLE</th></tr></thead><tbody>';
    
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
            $date = $job['formattedRelativeTime'];
            
            // Output data in HTML tables
            echo '<tr>';
            echo '<td width="25%">' . $company . '</td>';
            echo '<td width="15%">' . $location . '</td>';
            echo '<td width="10%">' . $date . '</td>';
            echo '<td width="50%"><a href="https://www.indeed.com/rc/clk?jk=' . $link . '">' . $title . '</a></td>';
            echo '</tr>';
        }
    }
    echo '</tbody></table>';
    
    unset($_GET['page']);
    $queryString = $_SERVER['PHP_SELF'] . '?' . http_build_query($_GET) . '&page=';
    
    echo '<ul class="pagination">';
    echo ($page > 1) ? '<li><a href="' . $queryString . ($page - 1) . '">&lsaquo; Prev</a>' : '';
    if ($totalPages <= 5) {
        addPages(1, $totalPages);
    } else {
        if ($page > 0 and $page <= $totalPages - 3) {
            // addPages(1, 1);
            // echo '<li><span class="disabled">...</span></li>';
            echo '<li class="active"><a href="' . $queryString . $page . '">' . $page . '</a></li>';
            addPages($page + 1, $page + 2);
            // addPages($page + 2, $page + 2);
            echo '<li><span class="disabled">...</span></li>';
            addPages($totalPages, $totalPages);
        } else if ($page > $totalPages - 3) {
            echo '<li><a href="' . $queryString . $page . '">1</a></li>';
            echo '<li><span class="disabled">...</span></li>';
            addPages($totalPages - 2, $totalPages);
        }
    }
    echo ($page < $totalPages) ? '<li><a href="' . $queryString . ($page + 1) . '">Next &rsaquo;</a>' : '';
    echo '</ul>';
    echo '</div>';
}
?>
