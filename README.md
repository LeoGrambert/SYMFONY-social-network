# Nos amis les oiseaux

Find instructions here : https://openclassrooms.com/projects/stage-ou-gestion-de-projet-en-equipe-specialite-developpement

Website description is alvaible at this adress : https://leogrambert.fr/projets/nao.php

<hr>

<h4>How to download and use this project</h4>

<h5>1. Download <a href="https://leogrambert.fr/front/projets/nosAmisLesOiseaux/projet5_CPMDev-master.zip">this</a></h5>

<h5>2. Unzip it where you want</h5>

<h5>3. Create database : <code>CREATE DATABASE nos_amis_les_oiseaux;</code> and import <a href="https://leogrambert.fr/front/projets/nosAmisLesOiseaux/species.sql">TAXREF file in SQL</a> (copy paste the content)</h5>

<h5>4. In your command prompt, go in Symfony/ and launch <code>composer install</code>.</h5>

<h5>5. In your command prompt, run the followings commands :<br>
<code>php bin/console doctrine:schema:update --force</code><br>
<code>php bin/console assets:install</code><br></h5>

<h5>6. Then launch the server with this command <code>php bin/console server:start</code> and go to this address : http://127.0.0.1:8000</h5>

If you want to use some tests users, import <a href="https://leogrambert.fr/front/projets/nosAmisLesOiseaux/nos_amis_les_oiseaux_user.sql">user table<a> (for example, you'll be able to connect with 'MichouDujardin' 'demo')
