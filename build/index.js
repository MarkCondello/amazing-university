/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/index.js":
/*!**********************!*\
  !*** ./src/index.js ***!
  \**********************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _scss_style_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../scss/style.scss */ "./scss/style.scss");
/* harmony import */ var _modules_MobileMenu__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./modules/MobileMenu */ "./src/modules/MobileMenu.js");
/* harmony import */ var _modules_GoogleMap__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./modules/GoogleMap */ "./src/modules/GoogleMap.js");
/* harmony import */ var _modules_Search__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./modules/Search */ "./src/modules/Search.js");
/* harmony import */ var _modules_MyNotes__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./modules/MyNotes */ "./src/modules/MyNotes.js");
/* harmony import */ var _modules_Likes__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./modules/Likes */ "./src/modules/Likes.js");
 // Our modules / classes
// import HeroSlider from './modules/HeroSlider'






const mobileMenu = new _modules_MobileMenu__WEBPACK_IMPORTED_MODULE_1__["default"](),
      // heroSlider = new HeroSlider(),
googleMap = new _modules_GoogleMap__WEBPACK_IMPORTED_MODULE_2__["default"](),
      siteSearch = new _modules_Search__WEBPACK_IMPORTED_MODULE_3__["default"](),
      myNotes = new _modules_MyNotes__WEBPACK_IMPORTED_MODULE_4__["default"](),
      likes = new _modules_Likes__WEBPACK_IMPORTED_MODULE_5__["default"](); // // Instantiate a new object using our modules/classes

jQuery(document).ready(function ($) {
  $('.hero-slider').owlCarousel({
    items: 1,
    autoplay: true
  });
});

/***/ }),

/***/ "./src/modules/GoogleMap.js":
/*!**********************************!*\
  !*** ./src/modules/GoogleMap.js ***!
  \**********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
$ = jQuery;

class GMap {
  constructor() {
    const self = this;
    $('.acf-map').each(function () {
      self.new_map($(this));
    });
  }

  new_map($el) {
    const $markers = $el.find('.marker'),
          args = {
      zoom: 16,
      center: new google.maps.LatLng(0, 0),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    },
          map = new google.maps.Map($el[0], args); // add a markers reference

    map.markers = [];
    var that = this; // that???

    $markers.each(function () {
      that.add_marker($(this), map);
    });
    this.center_map(map);
  }

  add_marker($marker, map) {
    const latlng = new google.maps.LatLng($marker.attr('data-lat'), $marker.attr('data-lng')),
          marker = new google.maps.Marker({
      position: latlng,
      map: map
    });
    map.markers.push(marker); // if marker contains HTML, add it to an infoWindow

    if ($marker.html()) {
      const infowindow = new google.maps.InfoWindow({
        content: $marker.html()
      }); // show info window when marker is clicked

      google.maps.event.addListener(marker, 'click', function () {
        infowindow.open(map, marker);
      });
    }
  }

  center_map(map) {
    const bounds = new google.maps.LatLngBounds();
    $.each(map.markers, function (i, marker) {
      // loop through all markers and create bounds
      var latlng = new google.maps.LatLng(marker.position.lat(), marker.position.lng());
      bounds.extend(latlng);
    });

    if (map.markers.length == 1) {
      map.setCenter(bounds.getCenter());
      map.setZoom(16);
    } else {
      map.fitBounds(bounds);
    }
  }

}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (GMap);

/***/ }),

/***/ "./src/modules/Likes.js":
/*!******************************!*\
  !*** ./src/modules/Likes.js ***!
  \******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);


class Likes {
  constructor() {
    this.events();
  }

  events() {
    jquery__WEBPACK_IMPORTED_MODULE_0___default()('.like-box').on('click', ev => this.ourClickDispatcher(ev));
  }

  ourClickDispatcher(ev) {
    const $likeBox = jquery__WEBPACK_IMPORTED_MODULE_0___default()(ev.target).closest('.like-box');
    console.log('dispatcher..', $likeBox, $likeBox.data('exists'));

    if ($likeBox.data('exists') === 'yes') {
      this.deleteLike();
    } else {
      this.createLike();
    }
  }

  createLike() {
    jquery__WEBPACK_IMPORTED_MODULE_0___default().ajax({
      beforeSend: xhr => {
        xhr.setRequestHeader('X-WP-Nonce', uniData.nonce); //Number used once
      },
      url: `${uniData.root_url}/wp-json/university/v1/add-like`,
      type: 'POST',
      success: response => {
        console.log('reached createLike', response);
      },
      error: response => {
        console.log('Unsuccessful: ', response);
      }
    });
  }

  deleteLike() {
    jquery__WEBPACK_IMPORTED_MODULE_0___default().ajax({
      beforeSend: xhr => {
        xhr.setRequestHeader('X-WP-Nonce', uniData.nonce); //Number used once
      },
      url: `${uniData.root_url}/wp-json/university/v1/delete-like`,
      type: 'DELETE',
      success: response => {
        console.log('reached deleteLike:', response);
      },
      error: response => {
        console.log('Unsuccessful: ', response);
      }
    });
  }

}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Likes);

/***/ }),

/***/ "./src/modules/MobileMenu.js":
/*!***********************************!*\
  !*** ./src/modules/MobileMenu.js ***!
  \***********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
$ = jQuery;

class MobileMenu {
  constructor() {
    this.menu = $(".site-header__menu");
    this.openButton = $(".site-header__menu-trigger");
    this.events();
  }

  events() {
    this.openButton.on("click", this.openMenu.bind(this));
  }

  openMenu() {
    this.openButton.toggleClass("fa-bars fa-window-close");
    this.menu.toggleClass("site-header__menu--active");
  }

}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (MobileMenu);

/***/ }),

/***/ "./src/modules/MyNotes.js":
/*!********************************!*\
  !*** ./src/modules/MyNotes.js ***!
  \********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
$ = jQuery;

class MyNotes {
  constructor() {
    this.events();
  }

  events() {
    // delegate click event to buttons so dynamically added notes register the click as well
    $('#my-notes').on('click', '.delete-note', ev => this.deleteNote($(ev.target)));
    $('#my-notes').on('click', '.edit-note', ev => this.handleEditBtnClick($(ev.target)));
    $('#my-notes').on('click', '.update-note', ev => this.saveNote($(ev.target)));
    $('.submit-note').on('click', ev => this.createNote($(ev.target)));
  }

  deleteNote($btn) {
    const $note = $btn.closest('li'),
          noteId = $note.data('noteId');
    $.ajax({
      beforeSend: xhr => {
        xhr.setRequestHeader('X-WP-Nonce', uniData.nonce); //Number used once
      },
      url: `${uniData.root_url}/wp-json/wp/v2/note/${noteId}`,
      type: 'DELETE',
      success: response => {
        console.log('Successful: ', response);
        $note.slideUp();

        if (response.notesCount < 5) {
          $('.note-limit-message').removeClass('active'); // remove limit message if shown and less than 5 notes
        }
      },
      error: response => {
        console.log('Unsuccessful: ', response);
      }
    });
  }

  handleEditBtnClick($btn) {
    const $note = $btn.closest('li');

    if ($note.data('editing')) {
      this.readOnlyNote($note);
    } else {
      this.editNote($note);
    }
  }

  editNote($note) {
    const $editBtn = $note.find('.edit-note');
    $note.data('editing', true);
    $note.find('.note-title-field, .note-body-field').removeAttr('readonly').addClass('note-active-field');
    $note.find('.update-note').addClass('update-note--visible');
    $editBtn.html('<i class="fa fa-times" aria-hidden="true"></i> Cancel');
  }

  readOnlyNote($note) {
    const $editBtn = $note.find('.edit-note');
    $note.data('editing', false);
    $note.find('.note-title-field, .note-body-field').attr('readonly', true).removeClass('note-active-field');
    $note.find('.update-note').removeClass('update-note--visible');
    $editBtn.html('<i class="fa fa-pencil" aria-hidden="true"></i> Edit.');
  }

  saveNote($btn) {
    const $note = $btn.closest('li'),
          noteId = $note.data('noteId'),
          updatedNote = {
      'title': $note.find('.note-title-field').val(),
      'content': $note.find('.note-body-field').val()
    };
    $.ajax({
      beforeSend: xhr => {
        xhr.setRequestHeader('X-WP-Nonce', uniData.nonce); //Number used once
      },
      url: `${uniData.root_url}/wp-json/wp/v2/note/${noteId}`,
      type: 'POST',
      data: updatedNote,
      success: response => {
        console.log('Successful: ', response);
        this.readOnlyNote($note);
      },
      error: response => {
        console.log('Unsuccessful: ', response);
      }
    });
  }

  createNote($btn) {
    const newNote = {
      'title': $('.new-note-title').val(),
      'content': $('.new-note-body').val(),
      'status': 'publish' // posts are set to draft by default

    };
    $.ajax({
      beforeSend: xhr => {
        xhr.setRequestHeader('X-WP-Nonce', uniData.nonce); //Number used once
      },
      url: `${uniData.root_url}/wp-json/wp/v2/note/`,
      type: 'POST',
      data: newNote,
      success: response => {
        console.log('Successful: ', response);
        $('.new-note-title, .new-note-body').val('');
        $(`
          <li data-note-id='${response.id}'>
            <input value="${response.title.raw}" class="note-title-field" readonly>
            <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</span>
            <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</span>
            <textarea class="note-body-field" readonly>${response.content.raw}</textarea>
            <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i> Save</span>
          </li>
        `).prependTo('#my-notes').hide().slideDown();
      },
      error: response => {
        console.log('Unsuccessful: ', response);
        const responseText = response.responseText;

        if (response.responseText === "You have reached your note limit") {
          $('.note-limit-message').addClass('active');
        }
      }
    });
  }

}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (MyNotes);

/***/ }),

/***/ "./src/modules/Search.js":
/*!*******************************!*\
  !*** ./src/modules/Search.js ***!
  \*******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
$ = jQuery;

class Search {
  constructor() {
    this.addSearchHTML(); //addSearchHTML must be added first for other elements to be referenced

    this.openButton = $('.js-search-trigger');
    this.closeButton = $('.search-overlay__close');
    this.searchOverlay = $('.search-overlay'); //add the events to the class when the page is loaded

    this.typingTimer;
    this.previousValue;
    this.isOverlayOpen = false;
    this.isSpinnerVisible = false;
    this.searchField = $('#search-term');
    this.searchResults = $('#search-overlay__results');
    this.events(); //events method must be called after references to dom elements are set
  }

  events() {
    $(document).on("keydown", this.keyPressDispatcher.bind(this)); //event for keydown on the page

    this.openButton.on("click", () => {
      this.openOverlay();
    });
    this.closeButton.on("click", () => {
      this.closeOverlay();
    });
    this.searchField.on("keyup", () => {
      this.typingLogic();
    });
  }

  openOverlay() {
    this.searchOverlay.addClass('search-overlay--active');
    $("body").addClass('body-no-scroll');
    this.isOverlayOpen = true;
    this.searchField.val('');
    setTimeout(() => this.searchField.focus(), 301);
  }

  closeOverlay() {
    this.searchOverlay.removeClass('search-overlay--active');
    $("body").removeClass('body-no-scroll');
    this.isOverlayOpen = false;
  }

  keyPressDispatcher(e) {
    //prevent the overlay from displaying when users click the 's' key on any other input fields
    if (e.keyCode === 83 && !this.isOverlayOpen && !$('input, textarea').is(':focus')) {
      //'s' key
      this.openOverlay();
    }

    if (e.keyCode === 27 && this.isOverlayOpen) {
      //'esc' key
      this.closeOverlay();
    }
  }

  typingLogic(e) {
    if (this.searchField.val() != this.previousValue) {
      clearTimeout(this.typingTimer); //do not allow for multiple setTimeouts
      //if there is a new search field value display the spinner and set the timeout for displaying results

      if (this.searchField.val()) {
        if (!this.isSpinnerVisible) {
          //do not reload the spinner if it is already visible
          this.searchResults.html('<div class="spinner-loader"></div>');
          this.isSpinnerVisible = true;
        }

        this.typingTimer = setTimeout(() => {
          this.getResults();
        }, 750);
      } else {
        this.searchResults.html('');
        this.isSpinnerVisible = false;
      }
    }

    this.previousValue = this.searchField.val();
  }

  getResults() {
    console.log(`${uniData.root_url}/wp-json/university/v1/search?term=${this.searchField.val()}`);
    $.getJSON(`${uniData.root_url}/wp-json/university/v1/search?term=${this.searchField.val()}`, results => {
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
                        ${item.author_name ? `by ${item.author_name}` : ''}
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
                        ${results.campuses.length ? '<ul class="link-list min-list">' : `<p>No Campuses match that search. <a href="${uniData.root_url}/campuses">View all Campuses.</a></p>`}
                        ${results.campuses.map(item => `<li><a href="${item.link}">${item.title}</a>
                        ${item.author_name ? `by ${item.author_name}` : ''}
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
            `);
      this.isSpinnerVisible = false;
    });
  }

  addSearchHTML() {
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
        </div>`);
  }

}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Search);

/***/ }),

/***/ "./scss/style.scss":
/*!*************************!*\
  !*** ./scss/style.scss ***!
  \*************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "jquery":
/*!*************************!*\
  !*** external "jQuery" ***!
  \*************************/
/***/ ((module) => {

module.exports = window["jQuery"];

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	(() => {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = (result, chunkIds, fn, priority) => {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var chunkIds = deferred[i][0];
/******/ 				var fn = deferred[i][1];
/******/ 				var priority = deferred[i][2];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every((key) => (__webpack_require__.O[key](chunkIds[j])))) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					var r = fn();
/******/ 					if (r !== undefined) result = r;
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	(() => {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"index": 0,
/******/ 			"./style-index": 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = (chunkId) => (installedChunks[chunkId] === 0);
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = (parentChunkLoadingFunction, data) => {
/******/ 			var chunkIds = data[0];
/******/ 			var moreModules = data[1];
/******/ 			var runtime = data[2];
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			if(chunkIds.some((id) => (installedChunks[id] !== 0))) {
/******/ 				for(moduleId in moreModules) {
/******/ 					if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 						__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 					}
/******/ 				}
/******/ 				if(runtime) var result = runtime(__webpack_require__);
/******/ 			}
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkId] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = self["webpackChunkamazing_university_wp_theme"] = self["webpackChunkamazing_university_wp_theme"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["./style-index"], () => (__webpack_require__("./src/index.js")))
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;
//# sourceMappingURL=index.js.map