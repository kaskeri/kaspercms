<?php

foreach (parse_ini_file(__DIR__ . '/.env') as $key => $value) {
    $_ENV[$key] = $value;
}

$conn = new mysqli(
    $_ENV["DB_HOST"],
    $_ENV["DB_USERNAME"],
    $_ENV["DB_PASSWORD"],
    $_ENV["DB_NAME"]
);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Käsitellään POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $link = trim($_POST["link"] ?? "");

    if ($link !== "") {
        $sql = "INSERT INTO links (link) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $link);
        $stmt->execute();
        $stmt->close();
    }


    header("Location: " . $_SERVER["PHP_SELF"] . "?success=1");
    exit;
}


$sql = "SELECT link FROM links";
$result = $conn->query($sql);
?>



<!DOCTYPE html>
<html>
    <head>
    <style>

        #logo{
            margin: 0, 0;
            height: 200px;
            width: 200px;
        }
        #linkki{
            font-size: 25px;
            padding: 25px;
        }
        .header{
            border: 2px solid black;
            display: flex;
            flex-direction: row;
        }
        .linkit{
            margin-top: 90px;
            margin-left: 50px;   
        }
        .artikkeli{
            margin-top: 40px;
            border: 2px solid black;
            display: flex;
            flex-direction: row;
        }
        .teksti{
            padding: 10px;
            flex-direction: column;
        }
        .artikkelikuva{
            
            width: 600px;
            height: 300px;
        }
        .footer{
            border: 2px solid black;
            margin-top: 40px;
            display: flex;
            flex-direction: row;
        }
        .footerteksti{
            width: 33%;
            padding-left: 10px;
        }
        .db-link{
            display: flex;
            flex-direction: column;
            width: 15%;
            text-align: center;
            padding-top: 2rem;
        }

        form{
            margin: 30px;
            padding: 10px;
            border: 1px solid black;
            background-color: whitesmoke;
        }
    </style>
    <script>

        let editFooterHeadingMode = false

        function handleFooterHeadingEdit() {
            editFooterHeadingMode = !editFooterHeadingMode

                const footerTitleElement = document.getElementById("footerTitle")
                const footerTitleInputElement = document.getElementById("footerTitleInput")

                if (!editFooterHeadingMode) {
                    const footerTitle = footerTitleInputElement.value
                    footerTitleElement.innerHTML = footerTitle
                    localStorage.setItem("footerTitle", footerTitle)
                }

                footerTitleElement.hidden = editFooterHeadingMode
                footerTitleInputElement.hidden = !editFooterHeadingMode
                document.getElementById("footerTitleButton").innerHTML = editFooterHeadingMode ? "Save" : "Edit"

                }
                function handleLoad() {
                    const footerTitle = localStorage.getItem("footerTitle")
                    document.getElementById("footerTitle").innerHTML = footerTitle
                    document.getElementById("footerTitleInput").value = footerTitle
                }

                addEventListener("load", handleLoad)

                        let editHeaderLogoMode = false

        function handleHeaderLogoEdit() {
            editHeaderLogoMode = !editHeaderLogoMode

                const headerLogoElement = document.getElementById("logo")
                const headerLogoButtonInputElement = document.getElementById("logoInput")

                if (!editHeaderLogoMode) {
                    const logo = headerLogoButtonInputElement.value
                    headerLogoElement.src = logo
                    localStorage.setItem("logo", logo)
                }

                headerLogoElement.hidden = editHeaderLogoMode
                headerLogoButtonInputElement.hidden = !editHeaderLogoMode
                document.getElementById("logoButton").innerHTML = editHeaderLogoMode ? "Save" : "Edit"

                }
                function handleLoad() {
                    const logo = localStorage.getItem("logo")
                    document.getElementById("logo").src = logo
                    document.getElementById("logoInput").innerHTML = logo
                }

                addEventListener("load", handleLoad)
            
    </script>
    </head>
<body>

    <div class="header">
<img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/f4/BMW_logo_%28gray%29.svg/1200px-BMW_logo_%28gray%29.svg.png"
id="logo">
<button id="logoButton" onclick="handleHeaderLogoEdit()">Edit</button>
<input id="logoInput" hidden>

    <div class="linkit">
<a id="linkki" href="#">Home</a>
<a id="linkki" href="#">Blog</a>
<a id="linkki" href="#">About</a>
</div>
    </div>

    <div class="artikkeli">
        <div class="teksti">
        <h2>BMW ARTIKKELI</h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.
             Suspendisse metus erat, posuere vel odio id, ornare blandit nibh.
              Fusce nisl neque, suscipit vel sagittis lobortis, aliquet id 
              nunc. Suspendisse potenti.</p>
            </div>
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/3/3f/BMW_7er_%28E38%29_20090314_front.jpg/1200px-BMW_7er_%28E38%29_20090314_front.jpg"
        class="artikkelikuva">
    </div>

    <div class="artikkeli">
        <div class="teksti">
        <h2>BMW ARTIKKELI</h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.
             Suspendisse metus erat, posuere vel odio id, ornare blandit nibh.
              Fusce nisl neque, suscipit vel sagittis lobortis, aliquet id 
              nunc. Suspendisse potenti.</p>
            </div>
        <img src="https://upload.wikimedia.org/wikipedia/commons/a/a6/1991-1996_BMW_318i_%28E36%29_sedan_%282011-04-02%29_01.jpg"
        class="artikkelikuva">
    </div>

    <div class="footer">
        <div class="footerteksti">

            <h2 id="footerTitle"></h2>
            <input id="footerTitleInput" hidden>
            <button id="footerTitleButton" onclick="handleFooterHeadingEdit()">Edit</button>

            <p>Lorem ipsum dolor elias on color. Binku Banki solero manki.
                Sipula pohvelo midrum kohvelo. Banane zidedine zidane kanane. 
                Muramasa siksakasa lurem us polem kus.
                © 2024, BMW COMPANI, all rights reserved
            </p></div>
            <?php
if ($result->num_rows > 0) {
  // output data of each row
while ($row = $result->fetch_assoc()) {
    echo '<a href="' . $row["link"] . '" class="db-link">' . $row["link"] . '</a><br><br>';
}
} else {
  echo "0 results";
}
$conn->close();
?>
<form method="POST">
    <label for="link">Linkki:</label><br>
    <input type="text" id="link" name="link" required><br><br>
    <button type="submit">Lähetä</button>
</form>
        </div>
    </div>
</body>
</html>