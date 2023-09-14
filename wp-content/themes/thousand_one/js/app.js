const html = document.documentElement
const body = document.body
const pageWrapper = document.querySelector('.main')
const header = document.querySelector('.header')
const firstScreen = document.querySelector('[data-observ]')
const burgerButton = document.querySelector('.icon-menu')
const menu = document.querySelector('.menu')
const lockPaddingElements = document.querySelectorAll('[data-lp]')


const toggleBodyLock = (isLock) => {
  FLS(`Попап ${isLock ? 'открыт' : 'закрыт'}`)
  const lockPaddingValue = window.innerWidth - pageWrapper.offsetWidth

  setTimeout(() => {
    if (lockPaddingElements) {
      lockPaddingElements.forEach((element) => {
        element.style.paddingRight = isLock ? `${lockPaddingValue}px` : '0px'
      })
    }
  
    body.style.paddingRight = isLock ? `${lockPaddingValue}px` : '0px'
    body.classList.toggle('lock', isLock)
  }, isLock ? 0 : 0)
}
// logger (Full Logging System) =================================================================================================================
function FLS(message) {
  setTimeout(() => (window.FLS ? console.log(message) : null), 0)
}

// Проверка браузера на поддержку .webp изображений =================================================================================================================
function isWebp() {
  // Проверка поддержки webp
  const testWebp = (callback) => {
    const webP = new Image()

    webP.onload = webP.onerror = () => callback(webP.height === 2)
    webP.src =
      'data:image/webp;base64,UklGRjoAAABXRUJQVlA4IC4AAACyAgCdASoCAAIALmk0mk0iIiIiIgBoSygABc6WWgAA/veff/0PP8bA//LwYAAA'
  }
  // Добавление класса _webp или _no-webp для HTML
  testWebp((support) => {
    const className = support ? 'webp' : 'no-webp'
    html.classList.add(className)

    FLS(support ? 'webp поддерживается' : 'webp не поддерживается')
  })
}

/* Проверка мобильного браузера */
const isMobile = {
  Android: () => navigator.userAgent.match(/Android/i),
  BlackBerry: () => navigator.userAgent.match(/BlackBerry/i),
  iOS: () => navigator.userAgent.match(/iPhone|iPad|iPod/i),
  Opera: () => navigator.userAgent.match(/Opera Mini/i),
  Windows: () => navigator.userAgent.match(/IEMobile/i),
  any: () =>
    isMobile.Android() ||
    isMobile.BlackBerry() ||
    isMobile.iOS() ||
    isMobile.Opera() ||
    isMobile.Windows(),
}
/* Добавление класса touch для HTML если браузер мобильный */
function addTouchClass() {
  // Добавление класса _touch для HTML если браузер мобильный
  if (isMobile.any()) {
    html.classList.add('touch')
  }
}

// Добавление loaded для HTML после полной загрузки страницы
function addLoadedClass() {
  window.addEventListener('load', () => {
    setTimeout(() => {
      html.classList.add('loaded')
    }, 0)
  })
}

// Получение хеша в адресе сайта
const getHash = () => {
  if (location.hash) {
    return location.hash.replace('#', '')
  }
}

// Указание хеша в адресе сайта
function setHash(hash) {
  hash = hash ? `#${hash}` : window.location.href.split('#')[0]
  history.pushState('', '', hash)
}

// Функция для фиксированной шапки при скролле =================================================================================================================
function headerFixed() {
  const headerStickyObserver = new IntersectionObserver(([entry]) => {
    header.classList.toggle('sticky', !entry.isIntersecting)
  })

  if (firstScreen) {
    headerStickyObserver.observe(firstScreen)
  }
}

// Универсальная функция для открытия и закрытия попапо =================================================================================================================
const togglePopupWindows = () => {
  document.addEventListener('click', ({ target }) => {
    if (target.closest('[data-type]')) {
      const popup = document.querySelector(
        `[data-popup="${target.dataset.type}"]`
      )

      if (document.querySelector('._is-open')) {
        document.querySelectorAll('._is-open').forEach((modal) => {
          modal.classList.remove('_is-open')
        })
      }

      popup.classList.add('_is-open')
      toggleBodyLock(true)

      if(!document.querySelector('.header-navBx').classList.contains('_is-open')) document.querySelector('.header-burger').classList.remove('active')
    }

    if (
      target.classList.contains('_overlay-bg') ||
      target.closest('.button-close')
    ) {
      const popup = target.closest('._overlay-bg')

      popup.classList.remove('_is-open')

      //дополнительные пересчеты конкретных поп апов
      document.querySelector('.header-catalog__button').classList.remove('active')
      if(!document.querySelector('.header-navBx').classList.contains('_is-open')) document.querySelector('.header-burger').classList.remove('active')

      if(popup.closest('.region')) {
        document.querySelector('.region').style.maxHeight =  0
        if(window.innerWidth <= 768) {
          let navBxMobile = document.querySelector('.header-navBx')
          navBxMobile.style.top = document.querySelector('.header').clientHeight + 'px'
          document.querySelector('.catalog').style.top = 0
        }else {
          document.querySelector('.catalog').style.top = document.querySelector('.header').clientHeight + 'px'
        }
      }

      toggleBodyLock(false)
    }
  })
}

// Модуль работы с меню (бургер) =======================================================================================================================================================================================================================
const menuInit = () => {
  if (burgerButton) {
    document.addEventListener('click', ({ target }) => {
      if (target.closest('.icon-menu')) {
        html.classList.toggle('menu-open')
        toggleBodyLock(html.classList.contains('menu-open'))
      }
    })
  }
}
const menuOpen = () => {
  toggleBodyLock(true)
  html.classList.add('menu-open')
}
const menuClose = () => {
  toggleBodyLock(false)
  html.classList.remove('menu-open')
}

class SwipeablePopup {
    constructor(selector, popupModal) {
        this.popup = document.querySelector(selector);
        this.startY = 0;
        this.startPopupY = 0; // Store the initial popup position
        this.touching = false;
        this.popupModal = document.querySelector(popupModal);
        this.startedInUpperArea = false;

        this.popup.addEventListener('touchstart', this.onTouchStart.bind(this));
        this.popup.addEventListener('touchmove', this.onTouchMove.bind(this));
        this.popup.addEventListener('touchend', this.onTouchEnd.bind(this));
    }

    onTouchStart(event) {
        this.startY = event.touches[0].clientY;
        this.startPopupY = this.popup.offsetTop; // Store the initial popup position
        const parentTopOffset = this.popup.parentElement.offsetTop; // Offset of parent container
        this.startedInUpperArea = this.startY <= (this.startPopupY + parentTopOffset + 50); // Check if started in upper area
        this.touching = true;
    }

    onTouchMove(event) {
        if (!this.touching || !this.startedInUpperArea) return;

        const deltaY = event.touches[0].clientY - this.startY;

        if (deltaY > 0 && deltaY <= 120) { // Check if moving downwards
            const newY = deltaY;
            this.popup.style.transform = `translate(0%, ${newY}px)`; // Apply translateY

            // Calculate opacity based on distance
            const opacity = 1 - (deltaY / 300); // Make it more transparent as deltaY approaches 100
            this.popup.style.opacity = opacity;
        }
    }

    onTouchEnd(event) {
        if (!this.touching || !this.startedInUpperArea) return;
        this.touching = false;
        if(!this.startedInUpperArea) this.startPopupY = 0

        const deltaY = event.changedTouches[0].clientY - this.startY;
        console.log(deltaY)
        if (Math.abs(deltaY) < 120) {
            this.popup.style.transform = `translate(0%, 0)`; // Return to the initial position
            this.popup.style.opacity = 1
        } else {
            this.popup.style.transform = `translate(0%, 100%)`;
            this.popupModal.classList.remove('_is-open')

            let isCloseAllModal = true
            document.querySelectorAll('[data-popup]').forEach(el_modal => {
                if(el_modal.classList.contains('_is-open')) isCloseAllModal = false
            })

            toggleBodyLock(!isCloseAllModal)

            setTimeout(() => {
                this.popup.removeAttribute('style')
            }, 500)
        }
        this.popup.removeAttribute('style')

    }
}

document.addEventListener('DOMContentLoaded', function(){
    if(document.querySelector('.region')) {
        // if(localStorage.getItem('region') == null) {
        //     setTimeout(() => {
        //         document.querySelector('.region').classList.add('_is-open')
        //         localStorage.setItem('region', '1')
        //     }, 5000)
        // }

        document.querySelector('.region').style.maxHeight =  document.querySelector('.region').scrollHeight + 'px'
    }

    if(document.querySelector('input[name="tel"]')) {
        $('input[name="tel"]').mask('+7 (999) 999 99-99')
    }

    if(document.querySelector('.services-box')) {
        let pageSize = window.innerWidth > 768 ? 12
                                               : 8
        $('#demo').pagination({
            // dataSource: function(done){
            //     $.ajax({
            //         type: 'GET',
            //         url: '../static/data.json',
            //         success: function(response){
            //             done(response.items);
            //         },
            //         error: function (e) {
            //             console.log(e)
            //         }
            //     })},
            dataSource: function(done){
                let result = [];
                let items = document.querySelectorAll('.services__item')
                for(let i = 0; i < items.length; i++){
                    result.push({
                        "name": items[i].querySelector('.services__item-title').textContent,
                        "address": items[i].querySelector('.services__item-info-address').textContent,
                        "check_link": items[i].querySelector('.services__item-titleBx img').src,
                        "service_link": items[i].querySelector('.services__item-titleBx img').src,
                    });
                }
                done(result);
            },
            pageSize: pageSize,
            callback: function(data, pagination) {
                var html = simpleTemplating(data);
                $('#data-container').html(html);
            },
            afterNextOnClick: function(){
                // function body
                $('html, body').stop().animate({
                    scrollTop: $('.services-inner').offset().top - 100
                }, 700);
            },
            afterPreviousOnClick : function(){
                // function body
                $('html, body').stop().animate({
                    scrollTop: $('.services-inner').offset().top - 100
                }, 700);
            },
            afterPageOnClick   : function(){
                // function body
                $('html, body').stop().animate({
                    scrollTop: $('.services-inner').offset().top - 100
                }, 700);
            }
        })
    }

    if(document.querySelector('.description')) {
        let descWrapper = document.querySelector('.description-wrapper'),
            buttonMore = document.querySelector('.description-more p')

        buttonMore.addEventListener('click', () => {
            descWrapper.classList.add('active')
            descWrapper.style.maxHeight = descWrapper.scrollHeight + 'px'
        })
    }

    if(document.querySelector('.services')) {
        ymaps.ready(init);
        function init(){
            // Создание карты.
            let myMap = new ymaps.Map("map", {
                    // Координаты центра карты.
                    // Порядок по умолчанию: «широта, долгота».
                    // Чтобы не определять координаты центра карты вручную,
                    // воспользуйтесь инструментом Определение координат.
                    center: [59.91174358283167,30.349143781250017],
                    // Уровень масштабирования. Допустимые значения:
                    // от 0 (весь мир) до 19.
                    zoom: 11,
                }),
                objectManager = new ymaps.ObjectManager({
                    // Чтобы метки начали кластеризоваться, выставляем опцию.
                    clusterize: true,
                    // ObjectManager принимает те же опции, что и кластеризатор.
                    gridSize: 32,
                    clusterDisableClickZoom: true
                })

            let myCollection = new ymaps.GeoObjectCollection();
            let arr_adress = [];
            $.ajax({
                url: "static/map.json"
            }).done(function(data) {

                let results = [];
                data.points.forEach(function(item, index) {
                    results.push(createPlacemark(item));
                });
                data.points.forEach(function(item, index) {
                    arr_adress.push(item);
                });
                myMap.geoObjects.add(myCollection);
                myMap.geoObjects.add(objectManager);
            });

            // Создать метку
            function createPlacemark(item) {
                let squareLayout = ymaps.templateLayoutFactory.createClass(item.infoPoint);
                let place = new ymaps.Placemark(
                    [item.latitude, item.longitude],
                    {
                        hintContent: item.help,
                    },
                    {
                        iconLayout: squareLayout,
                        iconShape: {
                            type: 'Rectangle',
                            coordinates: [
                                [15, 20], [30, 40]
                            ]
                        }
                    }
                );
                myCollection.add(place);
            }

            let thatCoordinates;
            myCollection.events.add('click', function (e) {
                //myCollection.get('target').properties.set('active', false);
                let that = e.get('target').properties.get('active');
                myCollection.each(function(item, index){
                    item.properties.set('active', false);

                    if(e.get('target') == item && !that){
                        e.get('target').properties.set('active', true);
                        thatCoordinates = e.get('coords');
                        let currentClickAddress = e.get('target').properties.get('hintContent'),
                            addresses = document.querySelectorAll('.services__item-info-main .services__item-info-address')

                        addresses.forEach(el => {
                            if(currentClickAddress === el.textContent) {
                                // $('.services-map-box').stop().animate({
                                //     scrollTop: $(el).offset().top
                                // }, 700);
                                // el.closest('.services__item').scrollIntoView({ behavior: 'smooth' });
                                if(window.innerWidth > 768) {
                                    document.querySelector('.services-map-box').scrollTo({
                                        top: el.closest('.services__item').offsetTop,
                                        behavior: 'smooth'
                                    });
                                }else{
                                    let items = document.querySelectorAll('.services-map-box .services__item')
                                    items.forEach(service_item => service_item.style.display = 'flex')
                                    items.forEach(service_item => {
                                        if(service_item.querySelector('.services__item-info-address').textContent !== currentClickAddress) {
                                            service_item.style.display = 'none'
                                        }else{
                                            toggleBodyLock(true)
                                        }
                                    })
                                    document.querySelector('.services-map-helper').classList.add('_is-open')
                                }
                            }
                        })
                        // let $address = document.querySelector('.map__address')
                        // $address.textContent = currentClickAddress

                        // if(window.innerWidth <= 600) {
                        //     $('html, body').stop().animate({
                        //         scrollTop: $('.map-inner').offset().top - 100
                        //     }, 700);
                        // }
                    }
                });
            });

            myMap.controls.remove('geolocationControl'); // удаляем геолокацию
            myMap.controls.remove('searchControl'); // удаляем поиск
            myMap.controls.remove('trafficControl'); // удаляем контроль трафика
            myMap.controls.remove('typeSelector'); // удаляем тип
            myMap.controls.remove('fullscreenControl'); // удаляем кнопку перехода в полноэкранный режим
            myMap.controls.remove('zoomControl'); // удаляем контрол зуммирования
            myMap.controls.remove('rulerControl'); // удаляем контрол правил
            // myMap.behaviors.disable(['scrollZoom']); // отключаем скролл карты (опционально)

            // myMap.geoObjects
            //     .add(myPlacemark)
        }


        if(window.innerWidth <= 768) {
            let servisesItemSwipeble = new SwipeablePopup('.services-map-box', '.services-map-helper')
        }
    }

    if(document.querySelector('.services-wrapper')) {
        let buttons         = document.querySelectorAll('.services-control-buttonBx__item'),
            blocksChanger   = document.querySelectorAll('.js-block-changer')

        buttons.forEach((button, index) => {
            button.addEventListener('click', () => {
                buttons.forEach(el => {el.classList.remove('active')}) // сброс активной кнопки
                button.classList.add('active')

                blocksChanger.forEach(el => {
                    el.style.opacity = 0
                    setTimeout(() => {
                        el.style.display = 'none'
                    }, 400)
                })

                setTimeout(() => {
                    blocksChanger[index].style.display = 'flex'
                }, 400)
                setTimeout(() => {
                    blocksChanger[index].style.opacity = 1
                }, 600)
            })
        })
    }

    if(document.querySelector('.service-map')) {
        let addr = $('.js-map').data('addr'),
            x = $(".js-map").data('x') === undefined ? 0 : $(".js-map").data('x'),
            y =$(".js-map").data('y') === undefined ? 0 : $(".js-map").data('y')

        if(!x || !y) {
            console.log(435345)
            // $.ajax({
            //     url: 'https://geocode-maps.yandex.ru/1.x?apikey=4a37db78-ee1a-4edb-a0c7-c9f4cb3c3d6f',
            //     method: 'get',
            //     dataType: 'json',
            //     success: function(data){
            //         console.log(data)
            //     }
            // });
        }

            return false;

        ymaps.ready(init);

        function init() {
            var Map = new ymaps.Map("service-map", {
                center: [x, y],
                zoom: 10,
                controls: [
                    'zoomControl',
                    'rulerControl',
                    'routeButtonControl',
                ]
            });

            let squareLayout = ymaps.templateLayoutFactory.createClass("<svg class='services__icon {% if properties.active %} active{% endif %}' width='44' height='53' viewBox='0 0 44 53' fill='none' xmlns='http://www.w3.org/2000/svg'><g filter='url(#filter0_d_246_59)'><circle cx='22.4547' cy='22.455' r='7.72745' fill='white'/><path d='M38.3127 29.5423L38.3127 29.5424L38.3172 29.5335C39.1242 27.9544 39.0612 25.915 37.7376 24.3296C36.8789 23.3011 36.889 21.8027 37.7614 20.7859C40.272 17.8596 38.4271 13.3196 34.587 12.974C33.2526 12.8539 32.2002 11.7873 32.0981 10.4514C31.8041 6.60696 27.2893 4.70121 24.3296 7.1722C23.3011 8.03086 21.8027 8.02078 20.7859 7.14837C17.8596 4.63781 13.3196 6.48268 12.974 10.3228C12.8539 11.6572 11.7873 12.7096 10.4514 12.8117C6.60696 13.1057 4.70121 17.6205 7.1722 20.5802C8.03086 21.6087 8.02078 23.107 7.14837 24.1239C5.4001 26.1617 5.77148 28.9705 7.42392 30.6029L19.9202 46.7551C21.2033 48.4135 23.7065 48.4153 24.9918 46.7586L37.3829 30.7883L37.3841 30.787C37.4023 30.7668 37.4279 30.7382 37.459 30.703C37.5209 30.633 37.606 30.535 37.6985 30.4236C37.8651 30.223 38.1198 29.9036 38.2726 29.6188L38.2726 29.6188L38.2772 29.61L38.3127 29.5423ZM29.1823 22.4549C29.1823 26.1704 26.1704 29.1823 22.4549 29.1823C18.7394 29.1823 15.7274 26.1704 15.7274 22.4549C15.7274 18.7394 18.7394 15.7274 22.4549 15.7274C26.1704 15.7274 29.1823 18.7394 29.1823 22.4549Z' fill='#0D2938' stroke='white' stroke-width='2'/></g><defs><filter id='filter0_d_246_59' x='0.998779' y='0.99707' width='42.9138' height='52.0029' filterUnits='userSpaceOnUse' color-interpolation-filters='sRGB'><feFlood flood-opacity='0' result='BackgroundImageFix'/><feColorMatrix in='SourceAlpha' type='matrix' values='0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0' result='hardAlpha'/><feOffset/><feGaussianBlur stdDeviation='2'/><feComposite in2='hardAlpha' operator='out'/><feColorMatrix type='matrix' values='0 0 0 0 0.0197222 0 0 0 0 0.0666667 0 0 0 0 0.0407323 0 0 0 0.12 0'/><feBlend mode='normal' in2='BackgroundImageFix' result='effect1_dropShadow_246_59'/><feBlend mode='normal' in='SourceGraphic' in2='effect1_dropShadow_246_59' result='shape'/></filter></defs></svg>");
            let mark = new ymaps.Placemark([x, y],
                {
                    hint: 'Сервисный центр ',
                    balloonContent: addr,
                },
                {
                    iconLayout: squareLayout,
                    iconShape: {
                        type: 'Rectangle',
                        coordinates: [
                            [15, 20], [30, 40]
                        ]
                    }
                }
            );

            Map.geoObjects.add(mark);
        }
    }

    if(document.querySelector('.header')) {
        let catalogButton = document.querySelectorAll('.header-catalog__button'),
            catalog = document.querySelector('.catalog')

        catalogButton.forEach(el => {
            el.addEventListener('click', () => {
                catalogButton.forEach(el => {el.classList.toggle('active')})
                catalog.classList.toggle('_is-open')
                toggleBodyLock(catalog.classList.contains('_is-open'))
            })
        })

        let burger = document.querySelector('.header-burger'),
            nav = document.querySelector('.header-navBx')

        burger.addEventListener('click', () => {
            burger.classList.toggle('active')
            nav.classList.toggle('_is-open')
            toggleBodyLock( nav.classList.contains('_is-open'))
        })

        if(window.innerWidth <= 768) {
            let swipeableWrapper = new SwipeablePopup('.catalog-wrapper', '.catalog')
        }

        let header = document.querySelector('.header'),
            navBxMobile = document.querySelector('.header-navBx')

        if(window.innerWidth <= 768) {
            navBxMobile.style.top = header.getBoundingClientRect().top + header.clientHeight + 'px'
            catalog.style.top = 0
        }else {
            catalog.style.top = header.getBoundingClientRect().top + header.clientHeight + 'px'
        }
    }


    if(document.querySelector('.modal.repair')) {
        if(window.innerWidth <= 768) {
            let swipeableRepair = new SwipeablePopup('.repair-formBx', '.repair')
            console.log(435345)
        }
    }
    if(document.querySelector('.services__item')) {
        let servicesBox = document.querySelector('.services-box')

        servicesBox.addEventListener('mouseover', (el) => {
            if(el.target.closest('.services__item')) {
                let currentItem = el.target.closest('.services__item')
                document.querySelectorAll('.services-box .services__item').forEach(item => item.style.opacity = '.65')
                currentItem.style.opacity = '1';
            }
        })
        servicesBox.addEventListener('mouseout', (el) => {
            if(el.target.closest('.services__item')) {
                document.querySelectorAll('.services-box .services__item').forEach(item => item.style.opacity = '1')
            }
        })
    }


    if(document.querySelector('.search-input')) {
        let inputs = document.querySelectorAll('.search-input')
        inputs.forEach(input => {
            input.addEventListener('focus', (e) => {
                e.target.parentNode.classList.add('active')
            })
            console.log()
            $(input).hideseek({ });
        })

        let searchBxs = document.querySelectorAll('.control-list')
        searchBxs.forEach(searchBx => {
            searchBx.addEventListener('click', e => {
                if(e.target.closest('.control-list__link')) {
                    e.target.closest('.control-selectBx').querySelector('input').value = e.target.closest('.control-list__link').textContent
                }
            })
        })
    }

    document.addEventListener('mouseup', function(evt) {
        if(document.querySelector('.search-input')) {
            let inputs = document.querySelectorAll('.search-input')
            inputs.forEach(input => {
                if (input !== document.activeElement && input.parentNode.querySelector('.control-list') !== evt.target) {
                    input.parentNode.classList.remove('active')
                }
            })
        }
    })

    if(document.querySelector('#services-control-input')) {
        $('#services-control-input').hideseek({ });
    }
});

function simpleTemplating(data) {
    var html = '';
    $.each(data, function(index, item){
        html += `
            <li>
                <div class="services__item">
                    <a href="#" class="services__item-titleBx"><img src="${item.check_link}" alt="">
                        <p class="services__item-title">${item.name}</p>
                    </a>
                    <div class="services__item-info"><img src="images/services/address.svg" alt="">
                        <div class="services__item-info-main">
                            <p>${item.address}</p>
                        </div>
                    </div>
                    <div class="services__item-info"><img src="images/services/clock.svg" alt="">
                        <div class="services__item-info-main">
                            <p>
                                Пн-Пт: 10:00 - 21:00 <br>
                                Сб-Вс: 10:00 - 21:00
                            </p>
                        </div>
                    </div>
                    <div class="services__item-info"><img src="images/services/star.svg" alt="">
                        <div class="services__item-info-main">
                            <p>5.0 (15)</p>
                        </div>
                    </div>
                    <div class="services__item-buttonBx">
                        <button class="services__item-feedback pb" data-type="repair">Оставить заявку</button>
                        <a href="tel: +7 (969) 999-42-80" class="services__item-phone"><img src="images/services/phone.svg" alt=""></a>
                    </div>
                </div>
            </li>
        `;
    });
    return html;
}

$('.svg img').each(function () {
    let $img = $(this);
    let imgID = $img.attr('id');
    let imgClass = $img.attr('class');
    let imgURL = $img.attr('src');

    $.get(imgURL, function (data) {
        // Get the SVG tag, ignore the rest
        let $svg = $(data).find('svg');

        // Add replaced image's ID to the new SVG
        if (typeof imgID !== 'undefined') {
            $svg = $svg.attr('id', imgID);
        }
        // Add replaced image's classes to the new SVG
        if (typeof imgClass !== 'undefined') {
            $svg = $svg.attr('class', imgClass + ' replaced-svg');
        }

        // Remove any invalid XML tags as per http://validator.w3.org
        $svg = $svg.removeAttr('xmlns:a');

        // Check if the viewport is set, if the viewport is not set the SVG wont't scale.
        if (!$svg.attr('viewBox') && $svg.attr('height') && $svg.attr('width')) {
            $svg.attr('viewBox', '0 0 ' + $svg.attr('height') + ' ' + $svg.attr('width'))
        }

        // Replace image with new SVG
        $img.replaceWith($svg);

    }, 'xml');

});

// Паралакс мышей ========================================================================================
// const mousePrlx = new MousePRLX({})
// =======================================================================================================

// Фиксированный header ==================================================================================
// headerFixed()
// =======================================================================================================

togglePopupWindows()
// =======================================================================================================