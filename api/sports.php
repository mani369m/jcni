<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="inc/app_logo/favicon.ico" type="image/gif" sizes="16x16" />
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>sports</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/moviesStyles.css" />
    <link rel="stylesheet" href="css/header.css" />
</head>
<body>
    <!--=============  NAVBAR =============-->
    <div class="header">
        <div class="brand"><span><h3 id="name">JIO</h3>&nbsp;<h6>Cinema</h6></span>
            <a id="menu" href="#"> <img id="menuimg" src="inc/app_logo/menu.png"> </a>
        </div>
        <div class="links" id="drop">
            <a href="index.php" class="btn text-light">JC-TV</a>
            <a href="movies.php" class="btn text-light">JC-Movies</a>
            <a href="tvShows.php" class="btn text-light">JC-Shows</a>
            <a href="sports.php" class="btn bg-danger text-light">JC-Sports</a>
        </div>
    </div>

        <div class="container">
    <div class="row"></div>
</div>

    <script src="https://code.jquery.com/jquery-3.6.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function getMovies() {
    const api = 'getsports.php';

    fetch(api)
        .then(response => response.json())
        .then(data => {
            displayMovies(data);
        })
        .catch(error => console.error('Error fetching data:', error));
}

function displayMovies(moviesData) {
    const rowElement = document.querySelector(".row");
    rowElement.innerHTML = '';

    moviesData.forEach(obj => {
        const { id, title, logourl } = obj;

        const channelCard = document.createElement("div");
        channelCard.className = "col-lg-3 col-md-4 col-sm-6";

        const image = document.createElement("img");
        image.className = "TV_Channels";
        image.src = logourl;

        const anchor = document.createElement('a');
        anchor.href = "./player/play.php?id=" + id;

        const channelName = document.createElement("p");
        channelName.className = "--ChannelName";
        channelName.innerHTML = title;

        anchor.appendChild(image);
        channelCard.appendChild(anchor);
        channelCard.appendChild(channelName);
        rowElement.appendChild(channelCard);
    });
}

getMovies();

$(document).ready(function(){
    $("#menuimg").click(function(){
        $("#drop").slideToggle("fast"); 
    });
});
</script>
</body>
</html>
