<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="inc/app_logo/favicon.ico" type="image/gif" sizes="16x16" />
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Live TV</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous" />
    
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/header.css" />
</head>
<body>
   <!--=============  NAVBAR =============-->
    
   <div class="header">
    <div class="brand"><span><h3 id="name">JIO</h3>&nbsp;<h6>Cinema</h6></span>
        <a id=""menu" href="#"> <img id="menuimg" src="inc/app_logo/menu.png"> </a> </div>
   
   <div class="links" id="drop">
          <a href="index.php" class="btn  bg-danger text-light">MX-TV</a>
          <a href="movies.php" class="btn text-light">JC-Moveis</a>
          <a href="tvShows.php" class="btn text-light">JC-Shows</a>
          <a href="sports.php" class="btn text-light">JC-Sports</a>
    </div>
</div>

   <!--============= search =============-->

    <div class="container">
        <div class="search-container">
            <input type="text" id="searchInput" placeholder="Search...">
        </div>

    <br>

    <div id="list" class="row">

    </div>

      <!-- Compiled and minified JavaScript -->
  <script src="https://code.jquery.com/jquery-3.6.1.js" ></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function fetchChannelsData() {
    const api = './data/channels.php';

    fetch(api, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data) {
            displayChannels(data);
        }
    })
    .catch(error => console.error('Error fetching data:', error));
}

function displayChannels(channels) {
    const rowElement = document.querySelector(".row");

    channels.forEach(channel => {
        const { id, logourl, title, url } = channel;

        const channelCard = document.createElement("div");
        channelCard.className = "col-6 col-sm-4 col-md-3 col-lg-2";
        channelCard.id = "--Channel-Card";

        const anchor = document.createElement('a');
        anchor.href = "#";  // Prevent default action and use JavaScript to navigate

        const image = document.createElement("img");
        image.src = logourl;
        image.className = "TV_Channels";

        const channelName = document.createElement("p");
        channelName.className = "--ChannelName";
        channelName.innerHTML = title;

        anchor.addEventListener('click', function(event) {
            event.preventDefault();
            postToPlayer(url);
        });

        anchor.appendChild(image);
        channelCard.appendChild(anchor);
        channelCard.appendChild(channelName);
        rowElement.appendChild(channelCard);
    });
}

function postToPlayer(url) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'Player/playlive.php'; 

    const urlInput = document.createElement('input');
    urlInput.type = 'hidden';
    urlInput.name = 'channel__url';
    urlInput.value = url;

    form.appendChild(urlInput);

    document.body.appendChild(form);
    form.submit();
}

// Initial fetch and display
fetchChannelsData();

</script>
   <script>
        $(document).ready(function(){
            $("#menuimg").click(function(){
               $("#drop").slideToggle("fast"); 
            });
        });
    </script>
    <script src="search.js"></script>
  </body>
</html>
