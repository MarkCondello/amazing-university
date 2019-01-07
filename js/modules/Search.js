import $ from 'jquery';

class Search {
    //1. constructor describes the objects properties and loads its methods
    constructor(){
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
        //this.searchField.on("keydown", this.typingLogic.bind(this));
        this.searchField.on("keyup", this.typingLogic.bind(this));

    }

    // 3. methods for the class object
    openOverlay(){
        //alert("clicked mag glass");
        this.searchOverlay.addClass('search-overlay--active');
        $("body").addClass('body-no-scroll');
        this.isOverlayOpen = true;
        //console.log("Overlay is open!");
    }

    closeOverlay(){
        this.searchOverlay.removeClass('search-overlay--active');
        $("body").removeClass('body-no-scroll');
        this.isOverlayOpen = false;
        //console.log("Overlay is closed!");
    }

    keyPressDispatcher(e){
        //console.log(e);
        //prevent the overlay from displaying when users click the 's' key on any other input fields
        if(e.keyCode === 83 && !this.isOverlayOpen && !$('input, textarea').is(':focus')) {
            this.openOverlay();
        }
        if(e.keyCode === 27 && this.isOverlayOpen) {
            this.closeOverlay();
        }
    }

    typingLogic(e){
        //do not allow for multiple setTimeouts
        if(this.searchField.val() != this.previousValue) { 
            clearTimeout(this.typingTimer);
            //if there is a search field value
            if(this.searchField.val() ){
                if(!this.isSpinnerVisible){
                    this.searchResults.html('<div class="spinner-loader"></div>');
                    this.isSpinnerVisible = true;
                } 
                this.typingTimer = setTimeout(this.getResults.bind(this), 2000);
            //else remove the results and spinner
            } else {
                this.searchResults.html('');
                this.isSpinnerVisible = false;
            }
        }
        this.previousValue = this.searchField.val();
    }

    getResults(){
        this.isSpinnerVisible = false;
        this.searchResults.html("search results here!");
    }
}

export default Search;

/*3 main sections
    constructor describes the object
    events for when users interact with the class
    methods or functions
*/