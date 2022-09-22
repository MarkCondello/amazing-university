
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
        console.log(`${uniData.root_url}/wp-json/university/v1/search?term=${this.searchField.val()}`)
        $.getJSON(`${uniData.root_url}/wp-json/university/v1/search?term=${this.searchField.val()}`, (results) => {
            this.searchResults.html(`
                <div class="row">
                    <div class="one-third">
                        <h2 class="search-overlay__section-title">General Information</h2>
                        ${results.generalInfo.length ? '<ul class="link-list min-list">' : '<p>No General Info matches that search.</p>'}
                        ${results.generalInfo.map(item => `<li><a href="${item.link}">${item.title}</a>
                        ${item.author_name ? `by ${item.author_name}` : ''}
                        </li>`).join('')}
                        ${results.generalInfo.length ? '</ul>' : ''}
                    </div>
                    <div class="one-third">
                        <h2 class="search-overlay__section-title">Programs</h2>
                        ${results.programs.length ? '<ul class="link-list min-list">' : `<p>No Programs match that search. <a href="${uniData.root_url}/programs">View all Programs.</a></p>`}
                        ${results.programs.map(item => `<li><a href="${item.link}">${item.title}</a>
                        ${item.author_name ? `by ${item.author_name}` : '' }
                        </li>`).join('')}
                        <h2 class="search-overlay__section-title">Professors</h2>
                        ${results.professors.length ? '<ul class="professor-cards">' : `<p>No Professors match that search.</p>`}
                        ${results.professors.map(item => `<li class="professor-card__list-item"><a class="professor-card" href="${item.link}">
                                <img class="professor-card__image" src="${item.thumbnail}" />
                                <span class="professor-card__name">${item.title}</span>
                            ${item.author_name ? ` <span>by ${item.author_name}</span>` : ''}
                            </a>
                            </li>`).join('')}
                        ${results.professors.length ? '</ul>' : ''}
                    </div>
                    <div class="one-third">
                        <h2 class="search-overlay__section-title">Campuses</h2>
                        ${results.campuses.length ? '<ul class="link-list min-list">' :  `<p>No Campuses match that search. <a href="${uniData.root_url}/campuses">View all Campuses.</a></p>`}
                        ${results.campuses.map(item => `<li><a href="${item.link}">${item.title}</a>
                        ${item.author_name ? `by ${item.author_name}` : '' }
                        </li>`).join('')}
                        ${results.campuses.length ? '</ul>' : ''}
                        <h2 class="search-overlay__section-title">Events</h2>
                        ${results.events.length ? '' : `<p>No Events match that search. <a href="${uniData.root_url}/events">View all Events.</a></p>`}
                        ${results.events.map(event => `
                        <div class="event-summary">
                            <a class="event-summary__date t-center" href="${event.link}">
                                <span class="event-summary__month">${event.event_month}</span>
                                <span class="event-summary__day">${event.event_day}</span>
                            </a>
                            <div class="event-summary__content">
                                <h5 class="event-summary__title headline headline--tiny">
                                    <a href="${event.link}">${event.title}  ${event.author_name ? ` - by ${event.author_name}` : ''}
                                    </a>
                                </h5>
                                <p>
                                    ${event.intro}
                                    <a href="${event.link}" class="nu gray">Learn more</a>
                                </p>
                            </div>
                        </div>`).join('')}
                    </div>
                </div>
            `)
            this.isSpinnerVisible = false
        })
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