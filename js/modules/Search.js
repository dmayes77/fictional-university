import $ from 'jquery';

class Search {
  // 1. describe and initiate the object
  constructor() {
    this.addSearchHTML();
    this.openButton = $('.js-search-trigger');
    this.closeButton = $('.search-overlay__close');
    this.searchOverlay = $('.search-overlay');
    this.searchField = $('#search-term');
    this.results = $('#search-overlay__results')
    this.events();
    this.isOverlayOpen = false;
    this.isSpinner = false;
    this.typingTimer;
    this.inputState;
  }

  // 2. events
  events() {
    this.openButton.on('click', this.openOverlay.bind(this));
    this.closeButton.on('click', this.closeOverlay.bind(this));
    this.searchField.on('keyup', this.typingLogic.bind(this));
    $(document).on('keydown', this.keyPress.bind(this));
  }

  // 3. methods
  typingLogic() {
    if (this.searchField.val() != this.inputState) {
      clearTimeout(this.typingTimer);
      
      if (this.searchField.val()) {
        if(!this.isSpinner) {
          this.results.html('<div class="spinner-loader"></div>');
          this.isSpinner = true;
        }
        this.typingTimer = setTimeout(this.getResults.bind(this), 750);
      } else {
        this.results.html('');
        this.isSpinner = false;
      }
    }
    this.inputState = this.searchField.val();
  }

  getResults() {
    $.when(
      $.getJSON(universityData.root_url + '/wp-json/wp/v2/posts?search=' + this.searchField.val()), 
      $.getJSON(universityData.root_url + '/wp-json/wp/v2/pages?search=' + this.searchField.val())
    ).then((posts, pages) => {
      let combinedResults = posts[0].concat(pages[0]);
        this.results.html(`
        <h2 class="search-overlay__section-title">General Inforamtion</h2>
        ${combinedResults.length ? '<ul class="link-list min-list">' : '<p>No general information matches that query</p>' }
          ${combinedResults.map(data => `<li><a href="${data.link}">${data.title.rendered}</a> ${data.type == 'post' ? `<small>by ${data.author}</small>` : ''}</li>`).join('')}
        ${combinedResults.length ? '</ul>' : '' }
      `);
      this.isSpinner = false;
    }, () => {
      this.results.html('<p>Unexpected Error! Please try again</p>')
    });
  }

  openOverlay() {
    this.searchOverlay.addClass('search-overlay--active');
    $('body').addClass('body-no-scroll');
    this.searchField.val('');
    setTimeout(() => this.searchField.focus(), 400);
    this.isOverlayOpen = true;
    console.log('open');
  }
  
  closeOverlay() {
    this.searchOverlay.removeClass('search-overlay--active');
    $('body').removeClass('body-no-scroll');
    this.isOverlayOpen = false;
    console.log('close');
  }

  keyPress(e) {
    if (e.keyCode == 83 && !this.isOverlayOpen && !$('input, textarea').is(':focus')) {
      this.openOverlay();
    }

    if (e.keyCode == 27 && this.isOverlayOpen) {
      this.closeOverlay();
    }
  }

  addSearchHTML() {
    $('body').append(`
      <div class="search-overlay">
        <div class="search-overlay__top">
          <div class="container">
            <i class="fas fa-search search-overlay__icon" aria-hidden="true"></i>
            <input type="text" class="search-term" placeholder="What are you looking for?" id="search-term">
            <i class="fas fa-window-close search-overlay__close" aria-hidden="true"></i>
          </div>
        </div>
        <div class="container">
          <div id="search-overlay__results"></div>
        </div>
      </div>
    `)
  }
}

export default Search;