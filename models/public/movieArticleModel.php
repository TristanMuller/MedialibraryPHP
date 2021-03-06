<?php

class MovieArticleModel extends MediaModel {

    private $duration;
    private $format;
    private $director;
    private $genders;

    public function __construct($id, $stock, $price, $title, $year, $state, $image, $duration, $support, $format, $director, $genders) {
        parent::__construct($id, $stock, $state, $price, $title, $support, $image, $year);

        $this->duration = $duration;
        $this->format = $format;
        $this->director = $director;
        $this->genders = $genders;
    }

    public function delete() {
        
    }

    public function insert() {
        
    }

    public function toCreate() {
        
    }

    public function toDelete() {
        
    }

    public function toModify() {
        $string = "<article class='layout-justify'>";

        $string .= "<div class='col-1 panel'>";
        $string .= "<img class='thumbnail' src='images/medias/" . $this->image . "'>";
        $string .= "</div>";

        $string .= "<div class='col-9 panel'>";
        $string .= "<form method='POST'><fieldset><legend>Article film n°" . $this->id . "</legend>";
        $string .= "<input hidden name='page' value='connection'>";
        $string .= "<input hidden name='type' value='movies'>";
        $string .= "<input hidden name='crudArticleId' value='" . $this->id . "'>";
        $string .= "Titre: <input name='crudArticleTitle' value=\"" . $this->title . "\">";
        $string .= "Année de sortie : <input name='crudArticleYear' type='number' value=\"" . $this->year . "\">";
        $string .= "Prix : <input name='crudArticlePrice' type='number' value=\"" . $this->price . "\">";
        $string .= "Stock : <input name='crudArticleStock' type='number' value=\"" . $this->stock . "\">";
        $string .= '<br>';
        $string .= "Image : <input name='crudArticleImage' type='text' value=\"" . $this->image . "\">";
        $string .= "Duree : <input name='crudArticleDuration' type='number' value=\"" . $this->duration . "\">";

        $string .= "Support : <select name='crudArticleSupport'>";
        $querySupport = "SELECT * FROM supports";
        $statementSupport = dataBaseConnection::execute($querySupport);
        if ($statementSupport) {
            $data = $statementSupport->FetchAll();

            for ($i = 0; $i < sizeof($data); $i++) {
                $string .= "<option value='" . $data[$i]["id_support"] . "'";

                if ($this->support == $data[$i]["nom_support"]) {
                    $string .= " selected";
                }

                $string .= ">" . $data[$i]["nom_support"] . "</option>";
            }
        }
        $string .= "</select>";

        $string .= "Format : <select name='crudArticleFormat'>";
        $queryFormat = "SELECT * FROM format_film";
        $statementFormat = dataBaseConnection::execute($queryFormat);
        if ($statementFormat) {
            $data = $statementFormat->FetchAll();

            for ($i = 0; $i < sizeof($data); $i++) {
                $string .= "<option value='" . $data[$i]["id_format"] . "'";

                if ($this->format == $data[$i]["nom_format"]) {
                    $string .= " selected";
                }

                $string .= ">" . $data[$i]["nom_format"] . "</option>";
            }
        }
        $string .= "</select>";

        $string .= "Realisateur : <select name='crudArticleRealisateur'>";
        $queryRealisateur = "SELECT * FROM personnes";
        $statementRealisateur = dataBaseConnection::execute($queryRealisateur);
        if ($statementRealisateur) {
            $data = $statementRealisateur->FetchAll();

            for ($i = 0; $i < sizeof($data); $i++) {
                $string .= "<option value='" . $data[$i]["id_personnne"] . "'";

                if ($this->director == $data[$i]["prenom"] . " " . $data[$i]["nom"]) {
                    $string .= " selected";
                }

                $string .= ">" . $data[$i]["prenom"] . " " . $data[$i]["nom"] . "</option>";
            }
        }
        $string .= "</select>";

        $string .= "</fieldset>";

        $string .= "<input type='submit' value='Sauvegarder les modifications'>";
        $string .= "</form>";
        $string .= "</div>";

        $string .= "</article>";
        return $string;
    }

    public function toShow() {
        $string = "";
        
        if ($this->state == "a") {
            $string .= "<article class='layout-justify'>";
            $string .= "<div class='col-1 panel'>";
            $string .= "<img class='thumbnail' src='images/medias/" . $this->image . "'>";
            $string .= "</div>";
            $string .= "<div class='col-8 panel'>";
            $string .= "<h3>" . $this->title . "</h3>";
            $string .= "<p>Année de sortie : " . $this->year . "</p>";
            $string .= "<p>Duree : " . $this->duration . " minutes</p>";
            $string .= "<p>Format : " . $this->format . "</p>";
            $string .= "<p>Réalisateur : " . $this->director . "</p>";
            $string .= "<p>Prix : " . $this->price . "€</p>";
            $string .= "<p>Genres : " . $this->genders . "</p>";
            $string .= "<p>Support : " . $this->support . "</p>";

            if ($this->stock <= 0) {
                $string .= "<p class='panel-blue'>En cours de réapprovisionnement</p>";
            } 
            else {
                $string .= "<p class='panel-green'>" . $this->stock . " articles restants" . "</p>";
            }

            $string .= "</div>";
            $string .= "</article>";
        }

        return $string;
    }

    public function toPanier() {        
        $string = "<td><p>Titre: " . $this->title . "</p></td>";
        $string .= "</td><td><p>Type: Film</p></td>";
        $string .= "</td><td><p>Prix: ". $this->price ."€</p></td>";
        return $string;
    }

    public function update() {
        $query = "UPDATE media SET title=\"" . $this->title . "\"";
        $query .= ", year=\"" . $this->year . "\"";
        $query .= ", price=\"" . $this->price . "\"";
        $query .= ", stock=\"" . $this->stock . "\"";
        $query .= ", id_support=\"" . $this->support . "\"";
        $query .= ", image=\"" . $this->image . "\"";
        $query .= " WHERE id=\"" . $this->id . "\";";
        $query .= "UPDATE media_film SET duree=\"" . $this->duration . "\"";

        $query .= ", id_format_film=\"" . $this->format . "\"";
        $query .= ", id_realisateur=\"" . $this->director . "\"";

        $query .= " WHERE id_media=\"" . $this->id . "\";";

        $statement = dataBaseConnection::execute($query);
        return $statement;
    }

    public static function search($maxResults, $titleFilter, $priceFilter, $yearFilter) {
        $articlesArray = array();

        $query = "SELECT id, title, price, state, year, stock, image, nom_support, duree, nom_format, nom, prenom FROM media";
        $query .= " JOIN supports ON supports.id_support = media.id_support";
        $query .= " JOIN media_film ON media_film.id_media = media.id";
        $query .= " JOIN format_film ON format_film.id_format = media_film.id_format_film";
        $query .= " JOIN personnes ON personnes.id_personnne = media_film.id_realisateur";

        if ($titleFilter) {
            $query .= " WHERE media.title LIKE '%" . $titleFilter . "%'";
        }
        if ($priceFilter) {
            $query .= " WHERE media.price=" . $priceFilter;
        }
        if ($yearFilter) {
            $query .= " WHERE media.year=" . $yearFilter;
        }

        $statement = dataBaseConnection::execute($query);

        if ($statement) {
            $data = $statement->fetchAll();

            $amount = sizeof($data);

            if ($maxResults) {
                if ($amount > $maxResults) {
                    $amount = $maxResults;
                }
            }

            for ($i = 0; $i < $amount; $i++) {
                $id = $data[$i]["id"];
                $support = $data[$i]["nom_support"];
                $image = $data[$i]["image"];
                $price = $data[$i]["price"];
                $state = $data[$i]["state"];
                $stock = $data[$i]["stock"];
                $title = $data[$i]["title"];
                $year = $data[$i]["year"];
                $duration = $data[$i]["duree"];
                $format = $data[$i]["nom_format"];
                $director = $data[$i]["prenom"] . " " . $data[$i]["nom"];
                $genders = "";

                $query2 = "SELECT id, nom_genre FROM media ";
                $query2 .= "JOIN media_film_genres ON media_film_genres.id_media_film = media.id ";
                $query2 .= "JOIN genres_f ON genres_f.id_genre = media_film_genres.id_genre_film";

                $statement2 = dataBaseConnection::execute($query2);

                if ($statement2) {
                    $data2 = $statement2->fetchAll();

                    foreach ($data2 as $gender) {
                        if ($gender["id"] == $id) {
                            $genders .= $gender["nom_genre"] . ", ";
                        }
                    }
                }

                $article = new MovieArticleModel($id, $stock, $price, $title, $year, $state, $image, $duration, $support, $format, $director, $genders);
                array_push($articlesArray, $article);
            }
        }

        return $articlesArray;
    }

    public static function import($id) {
        $article = null;
        
        $query = "SELECT * FROM media";
        $query .= " JOIN supports ON supports.id_support = media.id_support";
        $query .= " JOIN media_film ON media_film.id_media = media.id";
        $query .= " JOIN format_film ON format_film.id_format = media_film.id_format_film";
        $query .= " JOIN personnes ON personnes.id_personnne = media_film.id_realisateur";
        $query .= " WHERE media.id='" . $id . "'";

        $statement = dataBaseConnection::execute($query);

        if ($statement) {
            $data = $statement->fetchAll();

            $support = $data[0]["nom_support"];
            $image = $data[0]["image"];
            $price = $data[0]["price"];
            $state = $data[0]["state"];
            $stock = $data[0]["stock"];
            $title = $data[0]["title"];
            $year = $data[0]["year"];
            $duration = $data[0]["duree"];
            $format = $data[0]["nom_format"];
            $director = $data[0]["prenom"] . " " . $data[0]["nom"];
            $genders = "";

            $query2 = "SELECT id, nom_genre FROM media ";
            $query2 .= "JOIN media_film_genres ON media_film_genres.id_media_film = media.id ";
            $query2 .= "JOIN genres_f ON genres_f.id_genre = media_film_genres.id_genre_film";

            $statement2 = dataBaseConnection::execute($query2);

            if ($statement2) {
                $data2 = $statement2->fetchAll();

                foreach ($data2 as $gender) {
                    if ($gender["id"] == $id) {
                        $genders .= $gender["nom_genre"] . ", ";
                    }
                }
            }
            
            $article = new MovieArticleModel($id, $stock, $price, $title, $year, $state, $image, $duration, $support, $format, $director, $genders);
        }
        
        return $article;
    }

}
