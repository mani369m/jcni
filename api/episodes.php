<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="inc/app_logo/favicon.ico" type="image/gif" sizes="16x16" />
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Episodes</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous" />
    
    <link rel="stylesheet" href="css/searchStyles.css" />
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

    <!--============= MAIN CONTENT =============-->
    <div class="container">
        <br>
        <div class="row" id="episodesContainer">
            <!-- Episodes will be dynamically added here -->
        </div>
    </div>

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
            fetch('./utils/episodes.php?id=' + id)
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('episodesContainer');

                    data.forEach(episode => {
                        const episodeCard = document.createElement('div');
                        episodeCard.classList.add('col-lg-3', 'col-md-4', 'col-sm-6');

                        const episodeLink = document.createElement('a');
                        episodeLink.href = './player/play.php?id=' + episode.eid;

                        const episodeImage = document.createElement('img');
                        episodeImage.src = episode.thumb;
                        episodeImage.classList.add('TV_Channels');

                        const episodeTitle = document.createElement('p');
                        episodeTitle.classList.add('--ChannelName');
                        episodeTitle.textContent = episode.title;

                        episodeLink.appendChild(episodeImage);
                        episodeCard.appendChild(episodeLink);
                        episodeCard.appendChild(episodeTitle);

                        container.appendChild(episodeCard);
                    });

                    const modal = document.createElement('div');
                    modal.style.cssText = 'display: flex; align-items: center; justify-content: center; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 9999;';
                    
                    const content = document.createElement('div');
                    content.style.cssText = 'background-color: white; padding: 20px; border-radius: 5px; text-align: center;';
                    
                    const message = document.createElement('p');
                    message.textContent = '🎉 All episodes listed 🎉';
                    message.style.cssText = 'font-size: 18px; color: #007bff; font-family: "Gloock", serif;';

                    const closeButton = document.createElement('button');
                    closeButton.textContent = 'Close';
                    closeButton.style.cssText = 'margin-top: 10px; background-color: #007bff; color: white; border: none; padding: 5px 10px; border-radius: 5px; cursor: pointer;';

                    closeButton.addEventListener('click', function() {
                        document.body.removeChild(modal);
                    });

                    content.appendChild(message);
                    content.appendChild(closeButton);
                    modal.appendChild(content);
                    document.body.appendChild(modal);
                })
                .catch(error => console.error('Error fetching episodes:', error));
        } else {
            console.error('No id parameter found in the URL');
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.6.1.js"></script>
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
