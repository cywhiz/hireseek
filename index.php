<!DOCTYPE html>
<html lang="en">

<head>
    <title>HireSeek :: Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <meta name="keywords" content="hireseek, hiring, jobs, job search, indeed, internship, co-op, grad jobs, entry level jobs, student jobs, international jobs">
    <meta name="description" content="Find entry-level and professional jobs around the world through Indeed job search engine">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans">
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <div class="container">
        <div class="center">
            <img id="logo" src="img/logo.png">
        </div>
        <div>
            <form class="form-horizontal" role="form" action="results.php" method="get">
                <div class="form-group has-feedback">
                    <label class="col-sm-2 control-label">Job Title:</label>
                    <div class="col-sm-8">
                        <input class="form-control input-lg focused" id="query" name="query" type="text" placeholder="Enter job title" value="">
                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                        <label class="sublabel">
                            <input id="junior" name="junior" type="checkbox" value="">Entry-level
                        </label>
                        <label class="sublabel">
                            <input id="direct" name="direct" type="checkbox" value="">Direct hire
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Country:</label>
                    <div class="col-sm-8">
                        <select class="form-control input-lg focused" id="country" name="country"></select>
                    </div>
                </div>
                <div class="form-group has-feedback">
                    <label class="col-sm-2 control-label">City:</label>
                    <div class="col-sm-8">
                        <input class="form-control input-lg focused" id="city" name="city" type="text" placeholder="Enter city" value="">
                        <span class="glyphicon glyphicon-globe form-control-feedback"></span>
                    </div>
                </div>
                <div class="center">
                    <input class="submit" type="submit" id="submit" name="submit" value="">
                </div>
            </form>
        </div>
        <div class="center">
            Copyright &copy; <?php echo date('Y'); ?> hireseek.
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyCghkflS7rVe6msnwgFYmYwwA_noh5ZdvI"></script>
    <script src="js/autocomplete.js"></script>
    <script>
    $(function() {
        $.getJSON('countries.json', function(data) {
            var output = [];
            $.each(data, function(key, value) {
                output.push('<option value="' + key + '">' + value + '</option>');
            });

            $('#country').html(output.join(''));
        });
    });
    </script>
</body>
