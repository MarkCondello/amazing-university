import $ from 'jquery/dist/jquery';

class Search {
    //1. constructor describes the objects properties and loads its methods
    constructor(){
        //addSearchHTML must be added first for other elements to be referenced
        this.addSearchHTML();
        this.openButton = $('.js-search-trigger');
        this.closeButton = $('.search-overlay__close');
        this.searchOverlay = $('.search-overlay');
        //add the events to the class when the page is loaded
        this.isOverlayOpen = false;
        this.isSpinnerVisible = false;
        this.searchField = $('#search-term');
        this.typingTimer;
        this.searchResults = $('#search-overlay__results');
        this.previousValue;
        //events method must be called after references to dom elements are set
        this.events();
     }

    //2. events for when users interact with the class
    events(){
        //the constructor elements need to have their this value bound or else the program looks to the class itself instead of the elements
        this.openButton.on("click", this.openOverlay.bind(this));
        this.closeButton.on("click", this.closeOverlay.bind(this));
        //event for keydown on the page
        $(document).on("keydown", this.keyPressDispatcher.bind(this));
        //search term input field event
       // this.searchField.on("keydown", this.typingLogic.bind(this));
        this.searchField.on("keyup", this.typingLogic.bind(this));
    }

    // 3. methods for the class object
    openOverlay(){
        //alert("clicked mag glass");
        this.searchOverlay.addClass('search-overlay--active');
        $("body").addClass('body-no-scroll');
        this.isOverlayOpen = true;
        //console.log("Overlay is open!");
        //clear out the search term when opening the search overlay
        this.searchField.val('') ;
        //add focus to search input area after transition has completed
        setTimeout(()=> this.searchField.focus(), 301);
    }

    closeOverlay(){
        this.searchOverlay.removeClass('search-overlay--active');
        $("body").removeClass('body-no-scroll');
        this.isOverlayOpen = false;
        //console.log("Overlay is closed!");
    }

    keyPressDispatcher(e){
        //'s' key
        //prevent the overlay from displaying when users click the 's' key on any other input fields
        if(e.keyCode === 83 && !this.isOverlayOpen && !$('input, textarea').is(':focus')) {
            this.openOverlay();
        }
        //'esc' key
        if(e.keyCode === 27 && this.isOverlayOpen) {
            this.closeOverlay();
        }
    }

    typingLogic(e){
        //do not allow for multiple setTimeouts
        if(this.searchField.val() != this.previousValue) { 
            clearTimeout(this.typingTimer);
            //if there is a new search field value display the spinner and set the timeout for displaying results
            if(this.searchField.val() ){
                //do not reload the spinner if it is already visible
                if(!this.isSpinnerVisible){
                    this.searchResults.html('<div class="spinner-loader"></div>');
                    this.isSpinnerVisible = true;
                } 
                this.typingTimer = setTimeout(this.getResults.bind(this), 750);
            //else remove the results and the spinner
            } else {
                this.searchResults.html('');
                this.isSpinnerVisible = false;
            }
        }
        this.previousValue = this.searchField.val();
    }

    getResults(){  
        $.when(
            //CPT will need to be included here
            $.getJSON(uniData.root_url + '/wp-json/wp/v2/posts?search=' + this.searchField.val()), 
            $.getJSON(uniData.root_url + '/wp-json/wp/v2/pages?search=' + this.searchField.val()),
            $.getJSON(uniData.root_url + '/wp-json/wp/v2/program?search=' + this.searchField.val())
            )
            .then((posts, pages, programs)=>{
            //CPT results will need to be concatenated here
            var combinedResults = posts[0].concat(pages[0].concat(programs[0]));
            //console.log(posts);console.log(combinedResults);
            this.searchResults.html(`
            <h2 class="search-overlay__section-title">General Information</h2>

            ${combinedResults.length ? '<ul class="link-list min-list">' : '<p>no results found</p>'}
            ${combinedResults.map(item => `<li><a href="${item.link}"> 
            ${item.title.rendered}</a> 
            ${item.type == 'post'? `by ${item.authorName}` : ''}
            </li>`).join('')}
            ${combinedResults.length ? ' </ul>' : "" }
        
            `);
            this.isSpinnerVisible = false;
            }, ()=> {
                this.searchResults.html('<p>Unexpected error; please try again.</p>');
            }
        );
 /*        //relative url using wp_localize_script values added in functions.php
        $.getJSON(uniData.root_url + '/wp-json/wp/v2/posts?search=' + this.searchField.val(), (posts) => {//bind function to the class using es6 arrow function
           // console.log(posts.length, posts);
           $.getJSON(uniData.root_url + '/wp-json/wp/v2/pages?search=' + this.searchField.val(), pages =>{
           
           });
        }); */
    }

     addSearchHTML(){
         $('body').append(`
            <div class="search-overlay">
                <div class="search-overlay__top">
                    <div class="container">
                        <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
                        <input type="text" class="search-term" placeholder="What are you looking for?" id="search-term"/>
                        <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
                    </div>
                </div>

                <div class="container">
                    <div id="search-overlay__results">
                    </div>
                </div>
            </div>
         `)
     }
}

export default Search;

/*3 main sections to each class object
    a constructor which describes the object,
    events for when users interact with the class,
    and methods or functions which respond to events or other triggers

 
*/