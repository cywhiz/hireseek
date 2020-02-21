<!DOCTYPE html>
<html lang="en">

<head>
    <title>HireSeek :: Search Results</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <meta name="keywords" content="hireseek, hiring, jobs, job search, indeed, internship, co-op, grad jobs, entry level jobs, student jobs, international jobs">
    <meta name="description" content="Find entry-level and professional jobs around the world through Indeed job search engine">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/v/bs/dt-1.10.20/fh-3.1.6/r-2.2.3/datatables.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans">
    <link rel="stylesheet" href="css/results.css">
    <script src="https://gdc.indeed.com/ads/apiresults.js"></script>

    <?php
function setValue($field) {
    echo isset($_GET[$field]) ? $_GET[$field] : '';
}
function setChecked($field) {
    echo isset($_GET[$field]) ? 'checked' : '';
}
function isSelected($value) {
    return (isset($_GET['country']) && $_GET['country'] == $value) ? 'selected' : '';
}
function setSelected() {
    $json = json_decode(file_get_contents('countries.json'), true);
    echo '<select class="form-control" id="country" name="country">';
    foreach ($json as $k => $v) {
        echo '<option ' . isSelected($k) . ' value="' . $k . '">' . $v . '</option>';
    }
    echo '</select>';
}
?>
</head>

<body>
    <div class="container">
        <div>
            <img id="logo" src="img/logo_small.png">
        </div>
        <form class="form-inline" role="form" action="" method="get">
            <label>Title:</label>
            <div class="form-group has-feedback">
                <input class="form-control" id="query" name="query" type="text" placeholder="Enter job title" value="<?php setValue('query');?>">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <label class="sublabel">
                <input id="junior" name="junior" type="checkbox" <?php setChecked('junior');?> value="">Entry-level
            </label>
            <label class="sublabel">
                <input id="direct" name="direct" type="checkbox" <?php setChecked('direct');?> value="">Direct hire
            </label>
            <div class="row gap"></div>
            <label>Location:</label>
            <?php setSelected();?>
            <div class="form-group has-feedback">
                <input class="form-control" id="city" name="city" type="text" placeholder="Enter city" value="<?php setValue('city');?>">
                <span class="glyphicon glyphicon-globe form-control-feedback"></span>
            </div>
            <button type="submit" class="btn btn-primary btn-block-xs-only">
                <span class="glyphicon glyphicon-search"></span>
            </button>
        </form>
        <br>
        <div>
            <?php include 'table.php';?>
        </div>
        <div class="center">
            Copyright &copy; <?php echo date('Y'); ?> hireseek.
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyCghkflS7rVe6msnwgFYmYwwA_noh5ZdvI"></script>
    <script src="https://cdn.datatables.net/v/bs/dt-1.10.20/fh-3.1.6/r-2.2.3/datatables.min.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.10.20/sorting/natural.js"></script>
    <script src="js/autocomplete.js"></script>
    <script>
    $(function() {
        $('#results').DataTable({
            responsive: true,
            fixedHeader: true,
            iDisplayLength: 50,
            sDom: 'ft',
            'language': { search: 'Filter:' },
            'order': [[2, "asc"]],
            columnDefs: [{
                type: 'natural',
                targets: 2
            }]
        });
    });
    </script>
</body>
