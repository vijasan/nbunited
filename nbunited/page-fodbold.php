<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since 1.0.0
 */
get_header();
?>	<h1 id="entry-title" class="has-text-align-center">Foldboldhold</h1>
	<section id="primary" class="content-area foldboldhold entry">
		<main id="main" class="site-main entry-content">
            <nav id="filtrering" class="alignfull"><button class="filter valgt" data-foldboldhold="alle">Alle</button></nav>
        <article id="fodboldhold-oversigt" class="alignwide"></article>
    </main>
    <template>
        <section class="hold-container">
            <h2></h2>
            <img src="" alt="">
            <p></p>
        </section>
    </template>
    <script>
		const siteUrl = "<?php echo esc_url( home_url( '/' ) ); ?>";
        let fodboldhold = [];
        let categories = [];
        let indhold = [];
        const liste = document.querySelector("#fodboldhold-oversigt");
        const skabelon = document.querySelector("template");
        let filterfodboldhold = "alle";
        document.addEventListener("DOMContentLoaded", start);
        
        function start() {
            
            console.log("id er", <?php echo get_the_ID() ?>);
            console.log(siteUrl);
            
            getJson();
    
        }

        async function getJson() {
            //hent alle custom posttypes fodboldhold
            const url = siteUrl +"wp-json/wp/v2/fodboldhold?per_page=100";
            //hent basis categories
            const catUrl = "https://www.mirzah.dk/MMD/Afleveringer/nbunited/wp-json/wp/v2/categories";
            let response = await fetch(url);
            let catResponse = await fetch(catUrl);
            fodboldhold = await response.json();
            categories = await catResponse.json();
            visfodboldhold();
            opretKnapper();
        }
            function opretKnapper(){
            
            categories.forEach(cat=>{
               //console.log(cat.id);
                if(cat.name != "Uncategorized"){
                document.querySelector("#filtrering").innerHTML += `<button class="filter" data-fodboldhold="${cat.id}">${cat.name}</button>`
                }
            })
            addEventListenersToButtons();
        }

        function visfodboldhold() {
            console.log(fodboldhold);
            liste.innerHTML = "";
            console.log({filterfodboldhold});
            fodboldhold.forEach(fodboldhold => {
                //tjek filterfodboldhold
                if (filterfodboldhold == "alle"  || fodboldhold.categories.includes(parseInt(filterfodboldhold))) {
                    const klon = skabelon.cloneNode(true).content;
                    klon.querySelector("h2").textContent = fodboldhold.title.rendered;
                    klon.querySelector("p").innerHTML = fodboldhold.yoast_head_json.og_description;
                    klon.querySelector("section").addEventListener("click", () => {
                        location.href = fodboldhold.link;
                    })
                    liste.appendChild(klon);
                }
            })

        }
         function addEventListenersToButtons() {
            document.querySelectorAll("#filtrering button").forEach(elm => {
                elm.addEventListener("click", filtrering);
            })
        }
        
        function filtrering() {
			filterfodboldhold = this.dataset.fodboldhold
			if (this.dataset.fodboldhold === undefined){
				filterfodboldhold = "alle"
			}
            document.querySelector("h1").textContent = this.textContent;
            //fjern .valgt fra alle
            document.querySelectorAll("#filtrering .filter").forEach(elm => {
                elm.classList.remove("valgt");
            });
          
            //tilføj .valgt til den valgte
            this.classList.add("valgt");
            visfodboldhold();
        }
    </script>

	</section><!-- #primary -->

<?php
get_footer();