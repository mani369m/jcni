<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="inc/app_logo/favicon.ico" type="image/gif" sizes="16x16" />
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Search results</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    
    <link rel="stylesheet" href="css/seasonsDetails.css">
    <link rel="stylesheet" href="css/header.css" />
</head>
<body>
   <!--=============  NAVBAR =============-->
    
    <div class="header">
        <div class="brand">
            <span><h3 id="name">JIO</h3>&nbsp;<h6>Cinema</h6></span>
            <a id="menu" href="#"> <img id="menuimg" src="inc/app_logo/menu.png"> </a>
        </div>
   <div class="links" id="drop">
          <a href="index.php" class="btn text-light">JC-TV</a>
          <a href="movies.php" class="btn text-light">JC-Moveis</a>
          <a href="tvShows.php" class="btn text-light">JC-Shows</a>
          <a href="sports.php" class="btn text-light">JC-Sports</a>
    </div>
</div>

   <!--============= test =============-->

<div class="container">
    <div class="MovieDetails">
        <div class="img" id="img">
            <!-- Your image goes here -->
        </div>
        <div class="details">
            <div class="title">
                <h2 class="MovieTitle"></h2>
            </div>
            <div class="description">
               <span class="bg-dark text-light"><i class="fas fa-file-alt"></i> Description</span>
               <p class="MovieDescription"></p>
               <br>
               <span class="bg-dark text-light"><i class="fas fa-clock"></i> Time</span>
               <p class="MovieNow"></p>
               <br>
               <span class="bg-dark text-light"><i class="fas fa-film"></i> Genre</span>
               <p class="MovieGenre"></p>
            </div>
        </div>
    </div>
</div>

    <div class="row">
    </div>

  <script src="https://code.jquery.com/jquery-3.6.1.js" ></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function getParameterByName(name, url) {
            if (!url) url = window.location.href;
            name = name.replace(/[\[\]]/g, '\\$&');
            var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
                results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, ' '));
        }

        var id = getParameterByName('id');

        if (id) {
            fetch('./utils/season.php?id=' + id)
                .then(response => response.json())
                .then(data => {
                    var titleElement = document.querySelector(".MovieTitle");
                    var descriptionElement = document.querySelector(".MovieDescription");
                    var genreElement = document.querySelector(".MovieGenre");
                    var nowElement = document.querySelector(".MovieNow");
                    var posterElement = document.querySelector(".img");

                    if (data.length > 0) {
                        var cleanedTitle = data[0].title.replace(/-?S\d+$/, '');
                        document.title = "Watch " + cleanedTitle + " For Free";
                        titleElement.innerHTML = cleanedTitle;
                        descriptionElement.innerHTML = data[0].description;

                        genreElement.innerHTML = "Genre: " + data[0].genre;
                        nowElement.innerHTML = "Now: " + data[0].Now;

                        var image = document.createElement("img");
                        image.setAttribute("src", data[0].thumb);
                        image.setAttribute("alt", cleanedTitle);
                        posterElement.appendChild(image);
                    }

                    data.forEach(season => {
                        var seasonCard = document.createElement("div");
                        seasonCard.className = "--Season-Card";

                        var anchor = document.createElement('a');
                        anchor.href = "episodes.php?id=" + season.seasonid;

                        var seasonName = document.createElement("p");
                        seasonName.className = "--SeasonName";

                        seasonName.innerHTML = "Watch " + season.title;

                        seasonCard.appendChild(seasonName);
                        anchor.appendChild(seasonCard);
                        document.querySelector(".row").appendChild(anchor);
                    });
                })
                .catch(error => {
                    console.error("Error fetching or parsing data:", error);
                });
        } else {
            console.error("No id parameter found in the URL");
        }
    </script>
      <!-- Compiled and minified JavaScript -->
  <script src="https://code.jquery.com/jquery-3.6.1.js" ></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
   <script>
        $(document).ready(function(){
            $("#menuimg").click(function(){
               $("#drop").slideToggle("fast"); 
            });
        });
    </script>
  </body>
</html>
