import $ from 'jquery';

class Search {
    // section #1 : described and create/initiate our object
    constructor() {
        this.addSearchHTML();

        this.openButton = $(".js-search-trigger");
        this.close = $(".search-overlay__close");
        this.searchOverlay = $(".search-overlay");
        this.isOverlayOpen = false;

        this.searchField = $("#search-term");
        this.resultDiv = $('#search-overlay__results');
        this.typingTimer;
        this.isSpinnerVisible = false;
        this.previousValue;
        this.events();

    }

    // section #2 : events
    events() {
        this.openButton.on("click", this.openOverlay.bind(this));
        this.close.on("click", this.closeOverlay.bind(this));
        $(document).on("keydown", this.keyPressDispatcher.bind(this));

        this.searchField.on("keyup", this.typingLogic.bind(this));
    }

    // section #3: methods (function, action ...);
    typingLogic() {
        console.log(this.searchField.val());
        if (this.searchField.val() !== this.previousValue) {
            clearTimeout(this.typingTimer);
            if (this.searchField.val()) {
                if (!this.isSpinnerVisible) {
                    this.resultDiv.html('<div class="spinner-loader"></div>');
                    this.isSpinnerVisible = true;
                }
                this.typingTimer = setTimeout(this.getResults.bind(this), 750);
            } else {
                this.resultDiv.html('');
                this.isSpinnerVisible = false;
            }
        }
        this.previousValue = this.searchField.val();
    }


    getResults() {
        $.getJSON(universityData.root_url + '/wp-json/university/v1/search?term=' + this.searchField.val(), (result) => {
            this.resultDiv.html(`
                <div class="row">
                    <div class="one-third">
                        <h2 class="search-overlay__section-title">General Information</h2>
                        ${result.generalInfo.length ? '<ul class="link-list min-list">' : '<p>No general information matches that search. </p>'}
                        ${result.generalInfo.map(item => `<li><a href="${item.permalink}">${item.title}</a> ${item.postType == 'post' ? `by ${ item.authorName}` : ''} </li>`).join('')}
                        ${result.generalInfo.length ? '</ul>' : ''}
                    </div>
                    <div class="one-third">
                        <h2 class="search-overlay__section-title">Programs</h2>
                        ${result.programs.length ? '<ul class="link-list min-list">' : `<p>No programs matches that search. <a href="${universityData.root_url}/programs">View all programs</a>  </p>`}
                        ${result.programs.map(item => `<li><a href="${item.permalink}">${item.title}</a></li>`).join('')}
                        ${result.programs.length ? '</ul>' : ''}
                        
                        
                        <h2 class="search-overlay__section-title">Professors</h2>
                            ${result.professors.length ? '<ul class="professor-cards">' : '<p>No general information matches that search. </p>'}
                            ${result.professors.map(item => `
                                    <li class="professor-card__list-item">
                                        <a class="professor-card" href="${item.permalink}">
                                            <img src="${item.image}" alt="" class="professor-card__image">
                                            <span class="professor-card__name">${item.title}</span>
                                        </a>
                                    </li>
                                    `).join('')}
                            ${result.professors.length ? '</ul>' : ''}
                    </div>
                    <div class="one-third">
                        <h2 class="search-overlay__section-title">Campuses</h2>
                            ${result.campuses.length ? '<ul class="link-list min-list">' : `<p>No campuses matches that search. <a href="${universityData.root_url}/campuses">View all campuses</a> </p>`}
                            ${result.campuses.map(item => `<li><a href="${item.permalink}">${item.title}</a></li>`).join('')}
                            ${result.campuses.length ? '</ul>' : ''}
                            
                            
                        <h2 class="search-overlay__section-title">Events</h2>
                            ${result.events.length ? '' : '<p>No event matches that search. <a href="${universityData.root_url}/events">View all events</a> </p>'}
                            ${result.events.map(item => `
                                <div class="event-summary">
                                    <a class="event-summary__date t-center" href="${item.permalink}">
                                        <span class="event-summary__month">
                                           ${item.month}
                                        </span>
                                        <span class="event-summary__day">${item.day}</span>
                                    </a>
                                <div class="event-summary__content">
                                    <h5 class="event-summary__title headline headline--tiny"><a
                                            href="${item.permalink}">${item.title}</a></h5>
                                    <p>${item.description}
                                        <a href="${item.permalink}" class="nu gray">Learn more</a></p>
                                </div>
                            </div>
                                                            `).join('')}
                            ${result.events.length ? '</ul>' : ''}
                    </div>
                </div>    
            `
            )
        });

    }

    openOverlay() {
        this.searchOverlay.addClass("search-overlay--active");
        $("body").addClass('body-no-scroll');
        this.searchField.val('');
        setTimeout(() => this.searchField.focus(), 350);
        console.log('our open method jusr ran!');
        this.isOverlayOpen = true;
        return false;

    }

    closeOverlay() {
        this.searchOverlay.removeClass("search-overlay--active");
        $("body").removeClass('body-no-scroll');
        console.log('our close method just ran');
        this.isOverlayOpen = false;
    }

    keyPressDispatcher(e) {
        if (e.keyCode === 83 && !this.isOverlayOpen && !$("input, textarea").is(':focus')) {
            this.openOverlay();
        }
        if (e.keyCode === 27 && this.isOverlayOpen) {
            this.closeOverlay();
        }
    }

    addSearchHTML() {
        $('body').append(`
        <div class="search-overlay">
            <div class="search-overlay__top">
                <div class="container">
                    <i class="fa fa-search search-overlay__icon" aria-hiden="true"></i>
                    <input type="text" class="search-term" placeholder="What are you looking for?" id="search-term">
                    <i class="fa fa-window-close search-overlay__close" aria-hiden="true"></i>
                </div>
            </div>
            <div class="container">
                <div class="" id="search-overlay__results">
        
                </div>
            </div>
        </div>
        `);
    }
}

export default Search;