
$ = jQuery
class Search {
    constructor(){
        this.addSearchHTML() //addSearchHTML must be added first for other elements to be referenced
        this.openButton = $('.js-search-trigger')
        this.closeButton = $('.search-overlay__close')
        this.searchOverlay = $('.search-overlay')
        //add the events to the class when the page is loaded
        this.typingTimer
        this.previousValue
        this.isOverlayOpen = false
        this.isSpinnerVisible = false
        this.searchField = $('#search-term')
        this.searchResults = $('#search-overlay__results')
        this.events() //events method must be called after references to dom elements are set
     }
    events(){
        $(document).on("keydown", this.keyPressDispatcher.bind(this)) //event for keydown on the page
        this.openButton.on("click", ()=>{ this.openOverlay() })
        this.closeButton.on("click", ()=>{ this.closeOverlay() })
        this.searchField.on("keyup", ()=>{ this.typingLogic() })
    }
    openOverlay(){
        this.searchOverlay.addClass('search-overlay--active')
        $("body").addClass('body-no-scroll')
        this.isOverlayOpen = true
        this.searchField.val('')
        setTimeout(()=> this.searchField.focus(), 301)
    }
    closeOverlay(){
        this.searchOverlay.removeClass('search-overlay--active')
        $("body").removeClass('body-no-scroll')
        this.isOverlayOpen = false
    }
    keyPressDispatcher(e){
        //prevent the overlay from displaying when users click the 's' key on any other input fields
        if(e.keyCode === 83 && !this.isOverlayOpen && !$('input, textarea').is(':focus')) { //'s' key
            this.openOverlay()
        }
        if(e.keyCode === 27 && this.isOverlayOpen) { //'esc' key
            this.closeOverlay()
        }
    }
    typingLogic(e){
        if (this.searchField.val() != this.previousValue) {
            clearTimeout(this.typingTimer) //do not allow for multiple setTimeouts
            //if there is a new search field value display the spinner and set the timeout for displaying results
            if (this.searchField.val()){
                if (!this.isSpinnerVisible){ //do not reload the spinner if it is already visible
                    this.searchResults.html('<div class="spinner-loader"></div>')
                    this.isSpinnerVisible = true
                } 
                this.typingTimer = setTimeout(() => { this.getResults() }, 750)
            } else {
                this.searchResults.html('')
                this.isSpinnerVisible = false
            }
        }
        this.previousValue = this.searchField.val()
    }
    getResults(){
        $.when(
            $.getJSON(uniData.root_url + '/wp-json/wp/v2/posts?search=' + this.searchField.val()),
            $.getJSON(uniData.root_url + '/wp-json/wp/v2/pages?search=' + this.searchField.val()),
            $.getJSON(uniData.root_url + '/wp-json/wp/v2/program?search=' + this.searchField.val()),
            $.getJSON(uniData.root_url + '/wp-json/wp/v2/campus?search=' + this.searchField.val()),
            $.getJSON(uniData.root_url + '/wp-json/wp/v2/professor?search=' + this.searchField.val()),
            $.getJSON(uniData.root_url + '/wp-json/wp/v2/event?search=' + this.searchField.val()),
        )
        .then((posts, pages, programs, campuses, professors, event) => {
            this.isSpinnerVisible = false
            const authoredItems = ['post', 'event', 'page']
            const combinedResults = posts[0].concat(pages[0].concat(programs[0]).concat(campuses[0]).concat(professors[0]).concat(event[0]))
            // console.log(combinedResults)
            this.searchResults.html(`<h2 class="search-overlay__section-title">General Information</h2>
            ${combinedResults.length ? '<ul class="link-list min-list">' : '<p>no results found</p>'}
            ${combinedResults.map(item => `<li><a href="${item.link}">${item.title.rendered}</a>
            ${authoredItems.includes(item.type) ? `by ${item.authorName}` : '' }
            </li>`).join('')}
            ${combinedResults.length ? ' </ul>' : '' }`)
        }, () => {
            this.searchResults.html('<p>Unexpected error; please try again.</p>')
        });
        /* //relative url using wp_localize_script values added in functions.php
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
                    <input type="text" class="search-term" placeholder="What are you looking for?" id="search-term" autocomplete="off"/>
                    <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
                </div>
            </div>
            <div class="container">
                <div id="search-overlay__results"></div>
            </div>
        </div>`)
    }
}

export default Search;