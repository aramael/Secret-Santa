<?php
session_start();
$title = "Profile";
require_once "resources/config.php";
$time = date('U');
if (isset($_SESSION['uid'])){
	get_header();
	if ($time < $event_start){
		?>
        <div id="fb-root"></div>
        <script>(function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) {return;}
          js = d.createElement(s); js.id = id;
          js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1&appId=239083002819352";
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>

        <input type="text" class="sharelink" value="http://goo.gl/X9PEj"/>
        <img src="img/invite.png" />
        <p class="info">
        	Thanks for Registering, please remember the <a href="rules.php">rules</a>, and you will be emailed once you are assigned your person.
        </p>
        <img src="img/invite_facebook.png" />
        <div class="fb-like" data-href="<?php echo $project_url;?>" data-send="true" data-width="450" data-show-faces="true"></div>
        <br/>
        <img src="img/invite_tweet.png" />
        <br/>
        <center><a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo $project_url;?>register" data-text="<?php echo $twitter_message;?>" data-count="none">Tweet</a></center><script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>
        <?php
	}elseif ($time > $event_start){
		$user = new stdClass;
			try{
				$stmt = $dbh->prepare("SELECT match FROM users WHERE uid = :uid");
				$stmt->bindParam(":uid", $_SESSION['uid'], PDO::PARAM_INT, 11);
				$stmt->execute();
				$stmt->setFetchMode(PDO::FETCH_OBJ);
				$data = $stmt->fetch();
			}catch(PDOException $e){
				echo $e->getMessage();
			}
		$user->uid = $data->match;
			try{
				$stmt = $sdb->prepare("SELECT fname, lname, likes FROM users WHERE uid = :uid");
				$stmt->bindParam(":uid", $user->uid, PDO::PARAM_INT, 11);
				$stmt->execute();
				$stmt->setFetchMode(PDO::FETCH_OBJ);
				$data = $stmt->fetch();			
			}catch(PDOException $e){
				echo $e->getMessage();
			}
		$user->fname = $data->fname;
		$user->lname = $data->lname;
		$user->likes = $data->likes;
			if ($data->likes == "facebook"){
				try{
					$stmt = $dbh->prepare("SELECT facebookID, access_token FROM facebook WHERE uid = :uid");
					$stmt->bindParam(":uid", $user->uid, PDO::PARAM_INT, 11);
					$stmt->execute();
					$stmt->setFetchMode(PDO::FETCH_OBJ);
					$data = $stmt->fetch();			
				}catch(PDOException $e){
					echo $e->getMessage();
				}
		$user->access_token = $data->access_token;
		$user->facebookID = $data->facebookID;
			}
	?>
        <h1 class="name"><?php echo $user->fname." ".$user->lname;?></h1>
        <?php if (isset($user->access_token)){
            $fbprofile = facebook_info( $user->facebookID, "",$user->access_token);
        	if (isset($fbprofile->favorite_teams, $fbprofile->favorite_athlete)){?>
                <h2>Sports</h2>
					<?php if (isset($fbprofile->favorite_teams)){?>
                        <h3>Favorite Teams</h3>
                        <div class="row">
                            <?php 
                            $i = 1;
                            foreach ($fbprofile->favorite_teams as $team){
                                    echo "<div class='col gu1'><a href='https://www.facebook.com/profile.php?id=".$team->id."'><img src='https://graph.facebook.com/".$team->id."/picture?type=normal'/></a>";
                                    echo "<p><a href='https://www.facebook.com/profile.php?id=".$team->id."'>".$team->name."</a></p></div>";
                                if ($i == 4){?>
                                    </div>
                                        <div class="row">
                                            <div class="col gu4 more" id="teamsmorebtn">
                                                <center><a onclick="teams_more()">more</a></center>
                                            </div>
                                        </div>
                                    <div id="teamsmore" class="hide">
                                        <div class="row">
                                <?php }elseif ($i % 4 == 0){
                                    echo "</div><div class='row'>";	
                                }
                                $i++;
                            }
                            if ($i > 5){?>
                                </div>
                                    <div class="row">
                                        <div class="col gu4 more" id="teamslessbtn">
                                            <center><a onclick="teams_more()">less</a></center>
                                        </div>
                                    </div>						
                                </div>						
                            <?php }else{
                                echo "</div>";
                            }?>
					<?php }
					if (isset($fbprofile->favorite_athlete)){?>
                        <h3>Favorite Athletes</h3>
                        <div class="row">
                            <?php
                            $i = 1;
                                foreach ($fbprofile->favorite_athletes as $athlete){
                                        echo "<div class='col gu1'><a href='https://www.facebook.com/profile.php?id=".$athlete->id."'><img src='https://graph.facebook.com/".$athlete->id."/picture?type=normal'/></a>";
                                        echo "<p><a href='https://www.facebook.com/profile.php?id=".$athlete->id."'>".$athlete->name."</a></p></div>";
                                    if ($i == 4){?>
                                        </div>
                                            <div class="row">
                                                <div class="col gu4 more" id="athletemorebtn">
                                                    <center><a onclick="athlete_more()">more</a></center>
                                                </div>
                                            </div>
                                        <div id="athletemore" class="hide">
                                            <div class="row">
                                    <?php }elseif ($i % 4 == 0){
                                        echo "</div><div class='row'>";	
                                    }
                                    $i++;
                                }if ($i > 5){?>
                                    </div>
                                        <div class="row">
                                            <div class="col gu4 more" id="athletelessbtn">
                                                <center><a onclick="athlete_more()">less</a></center>
                                            </div>
                                        </div>						
                                <?php }else{
                                    echo "</div>";
                                }?>
					<?php }?>
            <?php }?>
            <hr/>
            <h2>Arts &amp; Entertainment</h2>
                <?php
                $music = facebook_info( $user->facebookID, "music",$user->access_token);
                $i = 1;
                if (isset($music->data)){?>
                    <h3>Music</h3>
                    <div class="row">
                        <?php foreach ($music->data as $musician){
                            echo "<div class='col gu1'><a href='https://www.facebook.com/profile.php?id=".$musician->id."'><img src='https://graph.facebook.com/".$musician->id."/picture?type=normal'/></a>";
                            echo "<p><a href='https://www.facebook.com/profile.php?id=".$musician->id."'>".$musician->name."</a></p></div>";
                            if ($i == 4){?>
                                </div>
                                    <div class="row">
                                        <div class="col gu4 more" id="musicmorebtn">
                                            <center><a onclick="music_more()">more</a></center>
                                        </div>
                                    </div>
                                <div id="musicmore" class="hide">
                                    <div class="row">
                            <?php }elseif ($i % 4 == 0){
                                echo "</div><div class='row'>";	
                            }
                            $i++;
                        }
                        if ($i > 5){?>
                            </div>
                                <div class="row">
                                    <div class="col gu4 more" id="musiclessbtn">
                                        <center><a onclick="music_more()">less</a></center>
                                    </div>
                                </div>
                            </div>						
                        <?php }else{
                            echo "</div>";
                        }?>
                <?php 
                }
                $books = facebook_info( $user->facebookID,"books",$user->access_token);
                $i = 1;
                if (isset($books->data)){
                ?>
                    <h3>Books</h3>
                    <div class="row">
                        <?php foreach ($books->data as $novel){
                            echo "<div class='col gu1'><a href='https://www.facebook.com/profile.php?id=".$novel->id."'><img src='https://graph.facebook.com/".$novel->id."/picture?type=normal'/></a>";
                            echo "<p><a href='https://www.facebook.com/profile.php?id=".$novel->id."'>".$novel->name."</a></p></div>";
                            if ($i == 4){?>
                                </div>
                                    <div class="row">
                                        <div class="col gu4 more" id="novelsmorebtn">
                                            <center><a onclick="novels_more()">more</a></center>
                                        </div>
                                    </div>
                                <div id="novelsmore" class="hide">
                                    <div class="row">
                            <?php }elseif ($i % 4 == 0){
                                echo "</div><div class='row'>";	
                            }
                            $i++;
                        }if ($i > 5){?>
                            </div>
                                <div class="row">
                                    <div class="col gu4 more" id="novelslessbtn">
                                        <center><a onclick="novels_more()">less</a></center>
                                    </div>
                                </div>						
                            </div>						
                        <?php }else{
                            echo "</div>";
                        }?>
                <?php
                }
                $movies = facebook_info( $user->facebookID, "movies",$user->access_token);
                $i = 1;
                if (isset($movies->data)){?>
                    <h3>Movies</h3>
                    <div class="row">
                        <?php foreach ($movies->data as $film){
                            echo "<div class='col gu1'><a href='https://www.facebook.com/profile.php?id=".$film->id."'><img src='https://graph.facebook.com/".$film->id."/picture?type=normal'/></a>";
                            echo "<p><a href='https://www.facebook.com/profile.php?id=".$film->id."'>".$film->name."</a></p></div>";
                            if ($i == 4){?>
                                    </div>
                                        <div class="row">
                                            <div class="col gu4 more" id="moviesmorebtn">
                                                <center><a onclick="movies_more()">more</a></center>
                                            </div>
                                        </div>
                                    <div id="moviesmore" class="hide">
                                        <div class="row">
                                <?php }elseif ($i % 4 == 0){
                                    echo "</div><div class='row'>";	
                                }
                                $i++;
                        }
                        if ($i > 5){?>
                            </div>
                                <div class="row">
                                    <div class="col gu4 more" id="movieslessbtn">
                                        <center><a onclick="movies_more()">less</a></center>
                                    </div>
                                </div>						
                            </div>						
                        <?php }else{
                            echo "</div>";
                        }?>
                <?php
                }
                $television = facebook_info( $user->facebookID, "television",$user->access_token);
                $i = 1;
                if (isset($television->data)){?>
                    <h3>Television</h3>
                    <div class="row">
                        <?php
                        foreach ($television->data as $tvshow){
                            echo "<div class='col gu1'><a href='https://www.facebook.com/profile.php?id=".$tvshow->id."'><img src='https://graph.facebook.com/".$tvshow->id."/picture?type=normal'/></a>";
                            echo "<p><a href='https://www.facebook.com/profile.php?id=".$tvshow->id."'>".$tvshow->name."</a></p></div>";
                            if ($i == 4){?>
                                </div>
                                    <div class="row">
                                        <div class="col gu4 more" id="tvshowmorebtn">
                                            <center><a onclick="tvshow_more()">more</a></center>
                                        </div>
                                    </div>
                                <div id="tvshowmore" class="hide">
                                    <div class="row">
                            <?php }elseif ($i % 4 == 0){
                                echo "</div><div class='row'>";	
                            }
                            $i++;
                        }if ($i > 5){?>
                            </div>
                                <div class="row">
                                    <div class="col gu4 more" id="tvshowlessbtn">
                                        <center><a onclick="tvshow_more()">less</a></center>
                                    </div>
                                </div>						
                            </div>						
                        <?php }else{
                            echo "</div>";
                        }?>
                <?php
                }
                $games = facebook_info( $user->facebookID, "games",$user->access_token);
                $i = 1;
                if (isset($games->data)){?>
                <h3>Games</h3>
                <div class="row">
                    <?php foreach ($games->data as $videogame){
                        echo "<div class='col gu1'><a href='https://www.facebook.com/profile.php?id=".$videogame->id."'><img src='https://graph.facebook.com/".$videogame->id."/picture?type=normal'/></a>";
                        echo "<p><a href='https://www.facebook.com/profile.php?id=".$videogame->id."'>".$videogame->name."</a></p></div>";
                        if ($i == 4){?>
                            </div>
                                <div class="row">
                                    <div class="col gu4 more" id="gamesmorebtn">
                                        <center><a onclick="games_more()">more</a></center>
                                    </div>
                                </div>
                            <div id="gamesmore" class="hide">
                                <div class="row">
                        <?php }elseif ($i % 4 == 0){
                            echo "</div><div class='row'>";	
                        }
                        $i++;
                    }if ($i > 5){?>
                        </div>
                            <div class="row">
                                <div class="col gu4 more" id="gameslessbtn">
                                    <center><a onclick="games_more()">less</a></center>
                                </div>
                            </div>					
                    <?php }else{
                        echo "</div>";
                    }?>
                <?php }?>
        <?php }else{
            echo '<hr /><h2 class="title">Likes &amp; Dislikes</h2><br/><p class="info">'.$user->likes.'</p>';
        }
	}
	get_footer();
}else{
	if ($time < $event_start){
		header("Location: register");
	}else{
		header("Location: login");
	}
}
?>