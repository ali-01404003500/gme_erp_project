
<script>
    $('#zero-config').DataTable({
        "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
            "<'table-responsive'tr>" +
            "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination paginate-area'p>>",
        "oLanguage": {
            "oPaginate": {
                "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
            },
            "sInfo": "Showing page _PAGE_ of _PAGES_",
            "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
            "sSearchPlaceholder": "Search...",
            "sLengthMenu": "Results :  _MENU_",
        },
        "stripeClasses": [],
        "lengthMenu": [7, 10, 20, 50],
        ordering: true,
        processing: true,
        info: false,
        lengthChange: false,
        pageLength: 20,
        paging: false,
        'drawCallback': function(settings) {
            const pageData = $('#zero-config').data('page');
            console.log(pageData);
            if (pageData) {
                $('.paginate-area').html(pageData);
            }
        }
    });

    $('#datatable-config').DataTable({
        "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
            "<'table-responsive'tr>" +
            "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination paginate-area'p>>",
        "oLanguage": {
            "oPaginate": {
                "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
            },
            "sInfo": "Showing page _PAGE_ of _PAGES_",
            "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
            "sSearchPlaceholder": "Search...",
            "sLengthMenu": "Results :  _MENU_",
        },
        "stripeClasses": [],
        "lengthMenu": [7, 10, 20, 50],
        ordering: true,
        processing: true,
        info: false,
        lengthChange: false,
        pageLength: 20,
        paging: $('#datatable-config').data('paging')??false,
        'drawCallback': function(settings) {
            // const pageData = $('#zero-config').data('page');
            // console.log(pageData);
            // if (pageData) {
            //     $('.paginate-area').html(pageData);
            // }
        }
    });


    //session show in toastr for warning
    @if (session()->has('warning'))
        $(document).ready(function() {
            toastr.warning('{{ session()->get('warning') }}');
        })
    @endif


    // session show in toastr
    @if (session()->has('success'))
        $(document).ready(function() {
            toastr.success('{{ session()->get('success') }}');
        })
    @endif

    @if (session()->has('error'))
        $(document).ready(function() {
            toastr.error('{{ session()->get('error') }}');
        })
    @endif



    $(document).ready(function() {
        $(".trumbowyg").trumbowyg({
            svgPath: "undefined" != typeof env && env.editorIconUrl ? env.editorIconUrl :
                "img/ui/icons.svg",
            btns: [
                ['viewHTML'],
                ['undo', 'redo'], // Only supported in Blink browsers
                ['formatting'],
                ['strong', 'em', 'del'],
                ['superscript', 'subscript'],
                ['link'],
                ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
                ['unorderedList', 'orderedList'],
                ['horizontalRule'],
                ['removeformat']
            ]
        });

        $('.trumbowyg').on('tbwinit', function() {
                // console.log($(this).attr('rows'), $(this).attr('id'));
                const editor = $(this).closest('.formElement-editor')
                const rows = parseInt($(this).attr('rows'));
                const height = rows * 60; // Adjust for padding/margins
                editor.find('.trumbowyg-box').css('height', height + 'px'); // Set your desired height
                editor.find('.trumbowyg-box').css('min-height', height+ 60 + 'px'); // Set your desired height
                editor.find('.trumbowyg-editor').css('min-height', height + 'px'); // Adjust for padding/margins
            });
    });


    // Autometic open sidebar menus
    $(document).ready(function() {
        var sidebarMenus = $("#sidebar-menus");
        sidebarMenus.find(".dropdown-toggle").attr("aria-expanded", "false"); //all expanded close
        var activeMenu = sidebarMenus.find("li.active");
        activeMenu.closest("li.menu").addClass("active");
        activeMenu.closest(".submenu").addClass("show");
        activeMenu.closest(".sub-submenu").addClass("show");
        activeMenu.closest("li.menu").find(".dropdown-toggle").attr("aria-expanded", "true");
    });



    // delete confirm sweet alart
    $(document).ready(function() {
        console.log("delete confirm attatch");
        $(".delete-confirm").each(function() {
            const el = this;
            {{-- console.log(el); --}}
            $(el).click((e) => {
                e.preventDefault();
                const url = $(el).data("action");
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!",
                }).then((result) => {
                    if (result.isConfirmed) {
                        if ($("form.delete-form").length > 0) {
                            $("form.delete-form").attr("action", url).submit();
                        } else {
                            // not found delete form alart
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong!',
                                footer: '<a href="">Why do I have this issue?</a>',
                            })
                        }
                    }
                })
            });
        });
    });

   $(document).ready(function() {
                flatpickr('.flattime', {
                    enableTime: true,
                    noCalendar: true,
                    altInput: true,
                    altFormat: "h:i K",
                    dateFormat: "H:i",
                    time_24hr: false
                });

                flatpickr('.flatdate', {
                    altInput: true,
                    altFormat: "Y-m-d",
                    dateFormat: "Y-m-d",
                });

                flatpickr('.flatdaterange', {
                    mode: "range",
                    altInput: true,
                    altFormat: "Y-m-d",
                    dateFormat: "Y-m-d",
                })

                flatpickr('.flatyear', {
                    shorthand: true, 
                    dateFormat: "Y", 
                    altFormat: "Y", 
                    theme: "dark" 
                })
                flatpickr('.flatmonth', {
                    altInput: true,
                    altFormat: "Y-m",
                    dateFormat: "Y-m", // Format to display year and month
                    plugins: [
                        new monthSelectPlugin({
                            shorthand: true, //defaults to false
                            dateFormat: "Y-m", //defaults to "F Y"
                            altFormat: "Y-m", //defaults to "F Y"
                            theme: "light" // defaults to "light"
                        })
                    ]
                });

                flatpickr('.flatmonthrange', {
                    mode: "range",
                    altInput: true,
                    altFormat: "Y-m",
                    dateFormat: "Y-m",
                    plugins: [
                        new monthSelectPlugin({
                            shorthand: true,
                            dateFormat: "Y-m",
                            altFormat: "Y-m",
                            theme: "light"
                        })
                    ]
                });

   })

    //Initialize Datepicker
    // flatpickr($('.datepicker'));
    // var f2 = flatpickr($('.dateTimePicker'), {
    //     enableTime: true,
    //     dateFormat: "Y-m-d H:i:S",
    // });
    // var f3 = flatpickr($('.timePicker'), {
    //     enableTime: true,
    //     noCalendar: true,
    //     dateFormat: "H:i",
    // });

    //date range
    // var f4 = flatpickr($('.daterange'), {
    //     mode: "range",
    //     dateFormat: "Y-m-d",
    // });

    $(document).ready(function() {
        $(".numberOnly").keypress(function(e) {
            if ((e.which != 8 && e.which != 0 && e.which != 110 && e.which != 190 && (e.which < 48 || e
                    .which > 57) && e.which != 46 && e.which != 101)) {
                return false;
            }
        });
    });

    document.querySelectorAll('.tom-select').forEach((el) => {
        let settings = {};
        new TomSelect(el, settings);
    });



    // Custom Script for Scroll to Top Button
    $(document).ready(function() {
        // Show or hide the scroll-to-top button based on scroll position
        $(window).scroll(function() {
            if ($(this).scrollTop() > 100) {
                $('#scrollToTopBtn').fadeIn();
            } else {
                $('#scrollToTopBtn').fadeOut();
            }
        });

        // Scroll to top when the button is clicked
        $('#scrollToTopBtn').click(function() {
            $('html, body').animate({
                scrollTop: 0
            }, 600);
            return false;
        });
    });
</script>

<script>

    window.idleCallback=function (fn, options) {
        return new Promise((resolve) => {
            requestIdleCallback((deadline) => {
                const result = fn(deadline);
                resolve(result);
            }, { timeout:2500,...options});
        });
    }

 
    function sub_opener(t) {
        t.preventDefault(),
            // $(this).parent().next("has-subchild").slideUp(),
            // $(this).parent().parent().children(".has-subchild").children("ul").slideUp(),
            // $(this).parent().parent().children(".has-subchild").removeClass("open"),
            // $(this).next().is(":visible") ? $(this).parent().removeClass("open") : $(this).parent().addClass("open"),
            // $(this).next().slideDown()
            $(this).parent().find("ul").slideToggle();
        $(this).parent().toggleClass("open");

    }
    // $(document).ready(function() {
    //     $(".has-subchild").not(".open").find("ul").hide();
    //     $(".has-subchild > a").on("click", sub_opener);
    // });
$(document).ready(function() {
    // Hide all nested lists except open ones
    $(".has-subchild").not(".open").find("> ul").hide();
    $(".has-subsubchild").not(".open").find("> ul").hide();
    $(".has-subsubsubchild").not(".open").find("> ul").hide();

    // Bind toggle click for each level
    $(".has-subchild > a").on("click", function(e) {
        e.preventDefault();
        $(this).parent().toggleClass("open").find("> ul").slideToggle(200);
    });

    $(".has-subsubchild > a").on("click", function(e) {
        e.preventDefault();
        $(this).parent().toggleClass("open").find("> ul").slideToggle(200);
    });

    $(".has-subsubsubchild > a").on("click", function(e) {
        e.preventDefault();
        $(this).parent().toggleClass("open").find("> ul").slideToggle(200);
    });
});


    function createToast2(t, i, o) {
        let n = "";
        console.log(i);
        const e = $(".notification-wrapper");
        "default" == t ? n =
            `\n      <div class="dm-notification-box notification-${t} notification-${toastCount}">\n        <div class="dm-notification-box__content">\n        <a href="#" class="dm-notification-box__close" data-toast="close">\n            <i class="uil uil-times"></i>\n        </a>\n            <div class="dm-notification-box__text">\n                <h6>Notification Title</h6>\n                <p>\n                    This is the content of the notification. This is the content of the notification. This is the content of the notification.\n                </p>\n            </div>\n        </div>\n      </div>\n      ` :
            "default" !== t && (n =
                `\n      <div class="dm-notification-box notification-${t} notification-${toastCount}">\n        <div class="dm-notification-box__content media">\n            <div class="dm-notification-box__icon">\n                <i class="uil uil-${i}"></i>\n            </div>\n            <div class="dm-notification-box__text media-body">\n                <h6>Notification Title</h6>\n                <p>\n                    This is the content of the notification. This is the content of the notification. This is the content of the notification.\n                </p>\n            </div>\n            <a href="#" class="dm-notification-box__close" data-toast="close">\n                <i class="uil uil-times"></i>\n            </a>\n        </div>\n    </div>\n    `
                ), o && (n =
                `\n        <div class="dm-notification-box notification-${t} notification-${toastCount}">\n            <div class="dm-notification-box__content">\n                <div class="dm-notification-box__text">\n                    <h6>Notification Title</h6>\n                    <p>\n                        This is the content of the notification. This is the content of the notification. This is the content of the notification.\n                    </p>\n                </div>\n                <div class="dm-notification-box__action d-flex justify-content-end">\n                    <button href="#" class="btn btn-xs btn-info custom-close" data-toast="close">Confirm</button>\n                </div>\n            </div>\n            <a href="#" class="dm-notification-box__close" data-toast="close">\n                <i class="uil uil-times"></i>\n            </a>\n        </div>\n        `
                ), e.append(n), toastCount++
    }


    function createNotification(type, icon, withButton, title, message, duration = Infinity) {
        let toastHtml = "";
        const notificationWrapper = $(".notification-wrapper");

        // Default notification without icon
        if (type === "default") {
            toastHtml = `
        <div class="dm-notification-box notification-${type} notification-${toastCount}">
            <div class="dm-notification-box__content">
                <a href="void(0)" class="dm-notification-box__close" data-toast="close">
                    <i class="uil uil-times"></i>
                </a>
                <div class="dm-notification-box__text">
                    <h6>${title}</h6>
                    <p>${message}</p>
                </div>
            </div>
        </div>
        `;
        }
        // Notification with icon
        else if (type !== "default") {
            toastHtml = `
        <div class="dm-notification-box notification-${type} notification-${toastCount}">
            <div class="dm-notification-box__content media">
                <div class="dm-notification-box__icon">
                    <i class="uil uil-${icon}"></i>
                </div>
                <div class="dm-notification-box__text media-body">
                    <h6>${title}</h6>
                    <p>${message}</p>
                </div>
                <a href="#" class="dm-notification-box__close" data-toast="close">
                    <i class="uil uil-times"></i>
                </a>
            </div>
        </div>
        `;
        }
        // Notification with button
        if (withButton) {
            toastHtml = `
        <div class="dm-notification-box notification-${type} notification-${toastCount}">
            <div class="dm-notification-box__content">
                <div class="dm-notification-box__text">
                    <h6>${title}</h6>
                    <p>${message}</p>
                </div>
                <div class="dm-notification-box__action d-flex justify-content-end">
                    <button href="#" class="btn btn-xs btn-info custom-close" data-toast="close">Confirm</button>
                </div>
            </div>
            <a href="#" class="dm-notification-box__close" data-toast="close">
                <i class="uil uil-times"></i>
            </a>
        </div>
        `;
        }

        // Append the toast to the wrapper
        notificationWrapper.append(toastHtml);
        toastCount++;

        // Automatically remove the toast after the specified duration (if not Infinity)
        if (duration !== Infinity) {
            setTimeout(function() {
                notificationWrapper.find(`.notification-${toastCount - 1}`).remove();
                toastCount--; // Decrement toastCount when a toast is removed
                console.log(`Toast removed automatically after ${duration}ms. Active toasts: ${toastCount}`);
            }, duration);
        }
    }

    $(document).ready(function() {
        const notificationWrapper = $(".notification-wrapper");
        // Add event listener for closing the toast
        notificationWrapper.on('click', '.dm-notification-box__close, .custom-close', function(event) {
            event.preventDefault();
            $(this).closest('.dm-notification-box').remove();
            toastCount--; // Decrement toastCount when a toast is removed
            console.log(`Toast removed manually. Active toasts: ${toastCount}`);
        });
    });
    let urgentTimer = 0;
   
    async function getCountNotification() {
           const notificationCount = Number(localStorage.getItem('notificationCount')) || 0;
           const allNotifications = localStorage.getItem('allNotifications') ? JSON.parse(localStorage.getItem('allNotifications')) : [];
           let count = await $.get("{{ route('get-notification-count') }}");
           if(count != notificationCount) {
               const newCount = count - notificationCount;
               if(count == 0) {
                   $('#notification-dropdown-icon').removeClass('nav-item-toggle icon-active')
               }else{
                   $('#notification-dropdown-icon').addClass('nav-item-toggle icon-active')
               }
               //read all notifications
               if(newCount > 0) {
                   const notifications = await $.get("{{ route('get-notifications') }}?limit=" + newCount);
                   
                   if(notifications.length > 0) {
                       notifications.forEach(notification => {
                           if(!allNotifications.find(item => item.id === notification.id)) {
                               createNotification("info", "info-circle", false, notification.title, notification.description, 5000);
                           }
                       });
                   }
               }
               const notificationAll = await $.get("{{ route('get-notifications') }}?limit=" + count);
               localStorage.setItem('allNotifications', JSON.stringify(notificationAll));
               $('#notification-list-data').empty();
               if(notificationAll.length > 0) {
                  count = notificationAll?.length || count;
                   notificationAll.forEach(notification => {
                       const actions = '{{route('notification.action', ':id')}}'.replace(':id', notification.id);
                       $('#notification-list-data').append(`
                       <li class="nav-notification__single nav-notification__single--unread d-flex flex-wrap">
                           <div class="nav-notification__type nav-notification__type--primary">
                               <img src="{{ asset('assets/img/svg/inbox.svg') }}" alt="inbox" class="svg">
                           </div>
                           <div class="nav-notification__details">
                               <p>
                                   <a href="${actions}" class="subject stretched-link text-truncate" style="max-width: 180px;">${notification.title}</a>
                                   <span>${notification.description}</span>
                               </p>
                               <p>
                                   <span class="time-posted">${moment(notification.created_at).fromNow()}</span>
                               </p>
                           </div>
                       </li>
                       `);
                   });
   
               }
               localStorage.setItem('notificationCount', count);
               $('#notification-count').text(count);
           }

           if(urgentTimer >= 30) {
               const urgentNotifications = allNotifications.filter(item => item.type === 'urgent');
                urgentNotifications.forEach(notification => {
                    createNotification("info", "info-circle", false, notification.title, notification.description, 5000);
                    console.log({notification});
                    
                });
                urgentTimer = 0;
           }
           urgentTimer++;
            
           
           
           // Check if any notification has type 'urgent'
           let timeoutInterval = 2000; // Default interval
          
           setTimeout(getCountNotification, timeoutInterval);
       }
    
    // function getCountNotification() {
    //     window.location.reload();
    // }

        $(document).ready(function () {
            @if(!config('app.debug'))
                getCountNotification();
            @endif
            const notificationCount = Number(localStorage.getItem('notificationCount')) || 0;
            $('#notification-count').text(notificationCount);

            if(notificationCount == 0) {
                $('#notification-dropdown-icon').removeClass('nav-item-toggle icon-active')
            }else{
                $('#notification-dropdown-icon').addClass('nav-item-toggle icon-active')
            }

            //read all notifications

            const notificationAll = localStorage.getItem('allNotifications') ? JSON.parse(localStorage.getItem('allNotifications')) : [];
            if(notificationAll.length > 0) {
                $('#notification-list-data').empty();
                notificationAll.forEach(notification => {
                    const actions = '{{route('notification.action', ':id')}}'.replace(':id', notification.id);
                    $('#notification-list-data').append(`
                    <li class="nav-notification__single nav-notification__single--unread d-flex flex-wrap">
                        <div class="nav-notification__type nav-notification__type--primary">
                            <img src="{{ asset('assets/img/svg/inbox.svg') }}" alt="inbox" class="svg">
                        </div>
                        <div class="nav-notification__details">
                            <p>
                                <a href="${actions}" class="subject stretched-link text-truncate" style="max-width: 180px;">${notification.title}</a>
                                <span>${notification.description}</span>
                            </p>
                            <p>
                                <span class="time-posted">${moment(notification.created_at).fromNow()}</span>
                            </p>
                        </div>
                    </li>
                    `);
                });
            }
        });
</script>

{{-- file uploader components --}}
<script>
class FileUploader{
    constructor(id, name, multiple, initialValue, csrfToken, customClass = null, loadDraftAndValue = false) {
        this.id = id;
        this.componentName = name;
        this.multiple = multiple;
        this.initialValue = initialValue;
        this.customClass = customClass;
        this.useDraftAndInitial = customClass? loadDraftAndValue : true;

        this.localStorageKey = `file-uploader-draft-urls-${this.componentName}${customClass? '-' + customClass : ''}-${window.location.href}`;

        this.container = customClass 
            ? document.querySelector(`.${customClass}`) 
            : document;
            console.log(this.container);
            
        
        this.fileInput = this.container.querySelector(`#file-input-${this.componentName}`);
        this.dropArea = this.container.querySelector(`#drop-area-${this.componentName}`);
        this.uploadedFilesContainer = this.container.querySelector(`#uploaded-files-${this.componentName}`);
        this.fileItemTemplate = this.container.querySelector(`#file-item-template-${this.componentName}`).content;
        this.hiddenInput = this.container.querySelector(`#hidden-input-${this.componentName}`);
        this.csrfToken = csrfToken;
        this.fileCounter = 0;

        this.initializeEventListeners();
        this.initializeExistingFiles();
    }

    initializeEventListeners() {
        this.dropArea.addEventListener('click', this.handleDropAreaClick.bind(this));
        this.fileInput.addEventListener('change', this.handleFileInputChange.bind(this));
        this.uploadedFilesContainer.addEventListener('click', this.handleFileActions.bind(this));

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            this.dropArea.addEventListener(eventName, this.preventDefaults.bind(this), false);
            this.container.addEventListener(eventName, this.preventDefaults.bind(this), false);
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            this.dropArea.addEventListener(eventName, this.highlight.bind(this), false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            this.dropArea.addEventListener(eventName, this.unhighlight.bind(this), false);
        });

        this.dropArea.addEventListener('drop', this.handleDrop.bind(this), false);

        const parentForm = this.findParentForm(this.dropArea);
        if (parentForm) {
            parentForm.addEventListener('submit', this.clearDraftUrls.bind(this));
        }
    }

    initializeExistingFiles() {
        if (this.useDraftAndInitial) {
            if (this.initialValue) {
                if (Array.isArray(this.initialValue) && this.multiple) {
                    this.initialValue.forEach(fileData => {
                        const fileUrl = this.extractFilePath(fileData);
                        if (fileUrl) this.addExistingFile(fileUrl);
                    });
                } else if (typeof this.initialValue === 'string' && this.initialValue.trim() !== '') {
                    this.addExistingFile(this.initialValue);
                } else if (this.initialValue !== null && typeof this.initialValue === 'object') {
                    const fileUrl = this.extractFilePath(this.initialValue);
                    if (fileUrl ) {
                        if(typeof fileUrl === 'string' && fileUrl.trim() !== '') {
                            this.addExistingFile(fileUrl);
                        } else  {
                            fileUrl.forEach(url => {
                                if (url) this.addExistingFile(url);
                            });
                        }
                    }
                }
            }else {
                this.loadDraftUrls();
            }
        }
        
        // If customClass is provided, start with an empty file list
    }

    loadDraftUrls() {
        const currentUrl = window.location.href;
        // const localStorageKey = `file-uploader-draft-urls-${this.componentName}-${currentUrl}`;
        const draftUrls = JSON.parse(localStorage.getItem(this.localStorageKey) || '[]');
        if (draftUrls) {
            const validDraftUrls = draftUrls.filter(url => url !== null);
            validDraftUrls.forEach(fileUrl => {
                // Check if the file URL is valid before adding
                console.log('Draft URL:', fileUrl, typeof fileUrl);
                
                if (fileUrl ) {
                        if(typeof fileUrl === 'string' && fileUrl.trim() !== '') {
                            this.addExistingFile(fileUrl);
                        } else  {
                            fileUrl.forEach(url => {
                                if (url) this.addExistingFile(url);
                            });
                        }
                    }
            });
        }
    }

    addExistingFile(fileUrl) {
        try {
            const fileId = `file-item-${this.componentName}-${this.fileCounter++}`;
            const fileItem = this.fileItemTemplate.cloneNode(true);
            const fileItemDiv = fileItem.querySelector('.file-item');

            
            let fileName = fileUrl.split('/').pop();
            let fileDisplayName = fileUrl.split('/').pop();
            const fileNameLength = 28;
            if (fileDisplayName.length > fileNameLength) {
                const fileNameStart = fileDisplayName.substring(0, fileNameLength/2 - 3);
                const fileNameEnd = fileDisplayName.substring(fileDisplayName.length - (fileNameLength/2 - 3));
                fileDisplayName = `${fileNameStart}...${fileNameEnd}`;
            }
            const fileExtension = fileName.split('.').pop().toLowerCase();
            let fileType = 'application/octet-stream';

            if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExtension)) {
                fileType = `image/${fileExtension === 'jpg' ? 'jpeg' : fileExtension}`;
            } else if (fileExtension === 'pdf') {
                fileType = 'application/pdf';
            }

            fileItemDiv.id = fileId;
            fileItemDiv.dataset.fileName = fileName;
            fileItemDiv.dataset.fileType = fileType;

            const fileIcon = fileItem.querySelector('.file-icon');
            fileIcon.src = this.getFileIcon(fileType);
            fileItem.querySelector('.file-name').textContent = fileDisplayName;

            const progressContainer = fileItem.querySelector('.upload-progress');
            progressContainer.style.display = 'none';

            const successIcon = fileItem.querySelector('.success-icon');
            const deleteButton = fileItem.querySelector('.delete-file-btn');
            const previewBtn = fileItem.querySelector('.preview-btn');
            const openBtn = fileItem.querySelector('.open-btn');
            const fileUrlInput = fileItem.querySelector('.file-url');

            successIcon.style.display = 'inline-block';
            deleteButton.style.display = 'inline-block';
            fileUrlInput.value = fileUrl;

            if (fileType.startsWith('image/')) {
                previewBtn.style.display = 'inline-block';
            } else {
                openBtn.style.display = 'inline-block';
            }

            this.uploadedFilesContainer.appendChild(fileItem);
            this.updateHiddenInput();
        } catch (error) {
            console.error('Error adding existing file:', error);
        }
    }

    handleDropAreaClick(event) {
        if (event.target === this.dropArea || event.target.closest(`#drop-area-${this.componentName}`) === this.dropArea) {
            this.fileInput.click();
        }
    }

    handleFileInputChange(event) {
        event.preventDefault();
        event.stopPropagation();
        if (this.fileInput.files && this.fileInput.files.length > 0) {
            const files = Array.from(this.fileInput.files);
            if (!this.multiple) this.clearAllFiles();
            files.forEach(file => {
                const fileId = this.addFileItem(file);
                this.uploadFile(file, fileId);
            });
            this.fileInput.value = '';
        }
    }

    handleFileActions(event) {
        const fileItem = event.target.closest('.file-item');
        if (!fileItem) return;

        if (event.target.classList.contains('delete-file-btn') || event.target.closest('.delete-file-btn')) {
            const fileUrl = fileItem.querySelector('.file-url').value;
            if (fileUrl) {
                fetch(fileUrl, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                    },
                })
                .then(response => {
                    if (!response.ok) return Promise.reject(response);
                    return response.json();
                })
                .then(() => {
                    fileItem.remove();
                    this.updateHiddenInput();
                })
                .catch(error => {
                    console.error('Error deleting file:', error);
                })
                .finally(() => {
                    fileItem.remove();
                    this.updateHiddenInput();
                });
            } else {
                fileItem.remove();
                this.updateHiddenInput();
            }
        } else if (event.target.classList.contains('preview-btn') || event.target.closest('.preview-btn')) {
            const fileUrl = fileItem.querySelector('.file-url').value;
            const fileName = fileItem.dataset.fileName;
            const previewImage = this.container.querySelector(`#previewImage-${this.componentName}`);
            const modalTitle = this.container.querySelector(`#imagePreviewModalLabel-${this.componentName}`);
            previewImage.src = fileUrl;
            modalTitle.textContent = fileName;
            if (typeof bootstrap !== 'undefined') {
                const imageModal = new bootstrap.Modal(this.container.querySelector(`#imagePreviewModal-${this.componentName}`));
                imageModal.show();
            } else {
                this.container.querySelector(`#imagePreviewModal-${this.componentName}`).style.display = 'block';
            }
        } else if (event.target.classList.contains('open-btn') || event.target.closest('.open-btn')) {
            const fileUrl = fileItem.querySelector('.file-url').value;
            window.open(fileUrl, '_blank');
        }
    }

    preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    highlight() {
        this.dropArea.classList.add('dragover');
    }

    unhighlight() {
        this.dropArea.classList.remove('dragover');
    }

    handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        if (files && files.length > 0) {
            const filesArray = Array.from(files);
            if (!this.multiple) this.clearAllFiles();
            filesArray.forEach(file => {
                const fileId = this.addFileItem(file);
                this.uploadFile(file, fileId);
            });
        }
    }

    deleteFiles(){
        const fileItems = this.uploadedFilesContainer.querySelectorAll('.file-item');
        fileItems.forEach(item => {
            const deleteButton = item.querySelector('.delete-file-btn');
            if (deleteButton) deleteButton.click();
            else item.remove();
        });
    }
    clearAllFiles() {
        this.deleteFiles();
        this.removeAllFiles();
        this.clearDraftUrls();
    }

    removeAllFiles() {
        const fileItems = this.uploadedFilesContainer.querySelectorAll('.file-item');
        fileItems.forEach(item => {
            item.remove();
            this.updateHiddenInput();
        });
        if (this.hiddenInput) this.hiddenInput.value = '';
        
    }

    clearDraftUrls() {
        const currentUrl = window.location.href;
        // const localStorageKey = `file-uploader-draft-urls-${this.componentName}-${currentUrl}`;
        localStorage.removeItem(this.localStorageKey);
    }

    getFileIcon(fileType) {
        if (fileType.startsWith('image/')) return 'https://img.icons8.com/color/30/000000/image-file.png';
        if (fileType === 'application/pdf') return 'https://img.icons8.com/color/30/000000/pdf-2--v1.png';
        if (fileType.startsWith('video/')) return 'https://img.icons8.com/color/30/000000/video-file.png';
        if (fileType.startsWith('audio/')) return 'https://img.icons8.com/color/30/000000/audio-file.png';
        return 'https://img.icons8.com/color/30/000000/document.png';
    }

    addFileItem(file) {
        const fileId = `file-item-${this.componentName}-${this.fileCounter++}`;
        const fileItem = this.fileItemTemplate.cloneNode(true);
        const fileItemDiv = fileItem.querySelector('.file-item');
        fileItemDiv.id = fileId;
        fileItemDiv.dataset.fileName = file.name;
        fileItemDiv.dataset.fileType = file.type;
        const fileIcon = fileItem.querySelector('.file-icon');
        fileIcon.src = this.getFileIcon(file.type);
        let fileDisplayName = file.name;
        const fileNameLength = 28;
        if (fileDisplayName.length > fileNameLength) {
            const fileNameStart = fileDisplayName.substring(0, fileNameLength/2 - 3);
            const fileNameEnd = fileDisplayName.substring(fileDisplayName.length - (fileNameLength/2 - 3));
            fileDisplayName = `${fileNameStart}...${fileNameEnd}`;
        }
        fileItem.querySelector('.file-name').textContent =fileDisplayName ;
        this.uploadedFilesContainer.appendChild(fileItem);
        return fileId;
    }

    updateFileProgress(fileId, percent) {
        
        const fileItem = this.container.querySelector('#'+fileId);
        if (fileItem) {
            const progressBar = fileItem.querySelector('.progress-bar');
            const progressPercentage = fileItem.querySelector('.progress-percentage');
            progressBar.style.width = percent + '%';
            progressPercentage.textContent = percent + '%';
        }
    }

    handleUploadComplete(fileId, response) {
        const fileItem = this.container.querySelector('#'+fileId);
        if (!fileItem) return;
        const progressContainer = fileItem.querySelector('.upload-progress');
        const successIcon = fileItem.querySelector('.success-icon');
        const deleteButton = fileItem.querySelector('.delete-file-btn');
        const fileUrl = fileItem.querySelector('.file-url');
        const previewBtn = fileItem.querySelector('.preview-btn');
        const openBtn = fileItem.querySelector('.open-btn');
        const fileType = fileItem.dataset.fileType;
        progressContainer.style.display = 'none';
        successIcon.style.display = 'inline-block';
        deleteButton.style.display = 'inline-block';
        fileUrl.value = response.path || '';
        if (fileType && fileType.startsWith('image/')) previewBtn.style.display = 'inline-block';
        else openBtn.style.display = 'inline-block';
        this.updateHiddenInput();
        this.storeDraftUrl(fileUrl.value);
    }

    storeDraftUrl(url) {
        const currentUrl = window.location.href;
        // const localStorageKey = `file-uploader-draft-urls-${this.componentName}-${currentUrl}`;
        let draftUrls = JSON.parse(localStorage.getItem(this.localStorageKey) || '[]');
        if (!this.multiple) {
            draftUrls = url ? [url] : [];
        } else {
            draftUrls = draftUrls.filter(draftUrl => draftUrl !== null);
            if (url) draftUrls.push(url);
        }
        localStorage.setItem(this.localStorageKey, JSON.stringify(draftUrls));
    }

    updateHiddenInput() {
        const fileItems = this.uploadedFilesContainer.querySelectorAll('.file-item');
        const fileUrls = Array.from(fileItems).map(item => item.querySelector('.file-url').value).filter(url => url);
        if (this.multiple) {
            if (this.hiddenInput.name.endsWith('[]')) {
                const parentForm = this.findParentForm(this.hiddenInput);
                if (parentForm) {
                    const existingInputs = parentForm.querySelectorAll(`input[name="${this.hiddenInput.name}"]`);
                    existingInputs.forEach((input, index) => { if (index > 0) input.remove(); });
                    this.hiddenInput.value = fileUrls.length > 0 ? fileUrls[0] : '';
                    for (let i = 1; i < fileUrls.length; i++) {
                        const newInput = document.createElement('input');
                        newInput.type = 'hidden';
                        newInput.name = this.hiddenInput.name;
                        newInput.value = fileUrls[i];
                        parentForm.appendChild(newInput);
                    }
                }
            } else {
                this.hiddenInput.value = JSON.stringify(fileUrls);
            }
        } else {
            this.hiddenInput.value = fileUrls.length > 0 ? fileUrls[0] : '';
        }
        // this.storeDraftUrl(fileUrls);
    }

    handleUploadError(fileId) {
        const fileItem = this.container.querySelector('#'+fileId);
        if (!fileItem) return;
        const progressContainer = fileItem.querySelector('.upload-progress');
        const errorIcon = fileItem.querySelector('.error-icon');
        progressContainer.style.display = 'none';
        errorIcon.style.display = 'inline-block';
    }

    findParentForm(element) {
        let parent = element.parentElement;
        while (parent) {
            if (parent.tagName === 'FORM') return parent;
            parent = parent.parentElement;
        }
        return null;
    }

    uploadFile(file, fileId) {
        const formData = new FormData();
        const url = "{{ route('upload_file') }}"; // Replace with actual upload route
        formData.append('file', file);
        if (this.csrfToken) formData.append('_token', this.csrfToken);
        const xhr = new XMLHttpRequest();
        xhr.open('POST', url, true);
        xhr.upload.onprogress = (e) => {
            if (e.lengthComputable) {
                let percent = (e.loaded / e.total) * 100;
                this.updateFileProgress(fileId, Math.round(percent));
            }
        };
        xhr.onload = () => {
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.response);
                    this.handleUploadComplete(fileId, response);
                } catch (error) {
                    console.error('Error parsing response:', error);
                    this.handleUploadError(fileId);
                }
            } else {
                console.error('Upload error', xhr.status, xhr.statusText);
                this.handleUploadError(fileId);
            }
        };
        xhr.onerror = () => {
            console.error("Network error during upload.");
            this.handleUploadError(fileId);
        };
        xhr.send(formData);
    }

    extractFilePath(fileData) {
        if (typeof fileData === 'string') return fileData;
        if (fileData && typeof fileData === 'object') {
            return fileData.path || fileData.url || fileData.file || '';
        }
        return '';
    }
}

</script>

    {{-- desable all kind of autocomplete --}}
    {{-- <script>
        $(document).ready(function() {
            console.log('disable all kind of autocomplete');
            $('input, textarea').each(function() {
                var $input = $(this);
                var originalName = $input.attr('name') || '';
                
                // Initial setup
                $input
                    .attr('autocomplete', 'new-password')
                    .attr('readonly', true)
                    .attr('name', 'field_' + new Date().getTime());
                
                // Handle focus
                $input.on('focus', function() {
                    $(this)
                        .removeAttr('readonly')
                        .attr('name', 'field_' + new Date().getTime())
                        .attr('autocomplete', 'new-password');
                });
                
                // Handle input
                $input.on('input', function() {
                    $(this).attr('name', 'field_' + new Date().getTime());
                });
                
                // Handle blur
                $input.on('blur', function() {
                    $(this)
                        .attr('readonly', true)
                        .attr('name', 'field_' + new Date().getTime());
                });
            });
        });
    </script> --}}

{{-- Additional scripts if needed for form validation --}}
@if ($errors->any())
    <script>
        //content loaded
        $(document).ready(function() {
            var errorsFieldName = '{!! json_encode($errors->keys()) !!}';
            var errorsFieldNameArray = JSON.parse(errorsFieldName);
            $('.tab-content .tab-pane').removeClass('show active');
            for (var i = 0; i < errorsFieldNameArray.length; i++) {
                // console.log();

                const errorFieldName = errorsFieldNameArray[i];
                if (errorFieldName.includes(".")) {
                    const arrayFileds = errorFieldName.split(".");
                    console.log(arrayFileds);
                    $($('input[name=\'' + arrayFileds[0] + '[]\']').get(arrayFileds[1])).addClass('is-invalid');
                    $($('select[name=\'' + arrayFileds[0] + '[]\']').get(arrayFileds[1])).addClass('is-invalid');
                    $($('textarea[name=\'' + arrayFileds[0] + '[]\']').get(arrayFileds[1])).addClass('is-invalid');

                    $($('input[name=\'' + arrayFileds[0] + '['+arrayFileds[1]+']\']').get(0)).addClass('is-invalid');
                    $($('select[name=\'' + arrayFileds[0] + '['+arrayFileds[1]+']\']').get(0)).addClass('is-invalid');
                    $($('textarea[name=\'' + arrayFileds[0] + '['+arrayFileds[1]+']\']').get(0)).addClass('is-invalid');


                } else {
                    $('input[name=' + errorFieldName + ']').addClass('is-invalid');
                    $('select[name=' + errorFieldName + ']').addClass('is-invalid');
                    $('textarea[name=' + errorFieldName + ']').addClass('is-invalid');

                  
                    $('input[name=' + errorFieldName + ']').closest(".tab-pane").addClass('show active');
                    $('select[name=' + errorFieldName + ']').closest(".tab-pane").addClass('show active');
                    $('textarea[name=' + errorFieldName + ']').closest(".tab-pane").addClass('show active');
                }


            }

            $('.tab-content .tab-pane.active').not(':first').removeClass('show active');
            const id = $('.tab-content .tab-pane.active').first().prop('id');
            $('.nav-tabs .nav-link').removeClass('active');
            $('.nav-tabs').find('a[href="#' + id + '"]').addClass('active');
        });
    </script>

@endif


@include('partials._pdf_printjs')