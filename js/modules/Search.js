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
    $.getJSON(universityData.root_url + '/wp-json/university/v1/search?term=' + this.searchField.val(), (results) => {
      this.results.html(`
      <div class="row">
        
        <div class="one-third">
          <h2 class="search-overlay__section-title">General Inforamtion</h2>
          ${results.generalInfo.length ? '<ul class="link-list min-list">' : '<p>No general information matches that query.</p>' }
            ${results.generalInfo.map(data => `<li><a href="${data.permalink}">${data.title}</a> ${data.postType == 'post' ? `<small>by ${data.author}</small>` : ''}</li>`).join('')}
          ${results.generalInfo.length ? '</ul>' : '' }
        </div>
        
        <div class="one-third">
          
          <h2 class="search-overlay__section-title">Programs</h2>
          ${results.programs.length ? '<ul class="link-list min-list">' : `<p>No programs match that query. <a href="${universityData.root_url}/programs"> View all programs</a></p>` }
            ${results.programs.map(data => `<li><a href="${data.permalink}">${data.title}</a></li>`).join('')}
          ${results.programs.length ? '</ul>' : '' }
          
          <h2 class="search-overlay__section-title">Professors</h2>
          ${results.professors.length ? '<ul class="professor-cards">' : `<p>No professors match that query.</p>` }
            ${results.professors.map(data => `
            <li class="professor-card__list-item">
            <a class="professor-card" href="${data.permalink}">
                <img class="professor-card__image" src="${data.image}">
                <span class="professor-card__name">${data.title}</span>
              </a>
            </li>
            `).join('')}
          ${results.professors.length ? '</ul>' : '' }
        </div>
        
        <div class="one-third">
          <h2 class="search-overlay__section-title">Campuses</h2>
          ${results.campuses.length ? '<ul class="link-list min-list">' : `<p>No campuses match that query. <a href="${universityData.root_url}/campuses"> View all campuses</a></p>` }
            ${results.campuses.map(data => `<li><a href="${data.permalink}">${data.title}</a></li>`).join('')}
          ${results.campuses.length ? '</ul>' : '' }
          
          <h2 class="search-overlay__section-title">Events</h2>
          ${results.events.length ? '' : `<p>No events match that query. <a href="${universityData.root_url}/events"> View all events</a></p>` }
            ${results.events.map(data => `
            <div class="event-summary">
            <a class="event-summary__date t-center" href="${data.permalink}">
              <span class="event-summary__month">${data.month}</span>
              <span class="event-summary__day">${data.day}</span>  
            </a>
            <div class="event-summary__content">
              <h5 class="event-summary__title headline headline--tiny"><a href="${data.permalink}">${data.title}</a></h5>
              <p>${data.description}<a href="${data.permalink}" class="nu gray">Learn more</a></p>
            </div>
          </div>
            `).join('')}
        </div>
      </div>
      `);
      this.isSpinner = false;
    })
  }

  openOverlay() {
    this.searchOverlay.addClass('search-overlay--active');
    $('body').addClass('body-no-scroll');
    this.searchField.val('');
    setTimeout(() => this.searchField.focus(), 400);
    this.isOverlayOpen = true;
    return false;
  }
  
  closeOverlay() {
    this.searchOverlay.removeClass('search-overlay--active');
    $('body').removeClass('body-no-scroll');
    this.isOverlayOpen = false;
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