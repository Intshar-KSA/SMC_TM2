@php use Carbon\Carbon; @endphp
    <!doctype html>
<html lang="en" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>تاسكلو | برنامج إدارة مهام شركات التسويق</title>

    <meta name="keywords" content="{{config('settings.meta_keywords_ar')}}"/>
    <meta name="description" content="{{config('settings.meta_description_ar')}}">
    <meta property="og:title" content="{{config('settings.meta_og_title_ar')}}">
    <meta property="og:type" content="{{config('settings.meta_og_type_ar')}}"/>
    <meta property="og:description" content="{{config('settings.meta_og_description_ar')}}">
    <meta property="og:image" content="{{url('/')."/uploads/meta/".config('settings.meta_og_image_ar')}}">
    <meta property="og:url" content="{{config('settings.meta_og_url_ar')}}">

    <meta name="twitter:title" content="{{config('settings.meta_twitter_title_ar')}}">
    <meta name="twitter:description" content=" {{config('settings.meta_twitter_description_ar')}}">
    <meta name="twitter:image" content="{{url('/')."/uploads/meta/".config('settings.meta_twitter_image_ar')}}">
    <meta name="twitter:card" content="{{config('settings.meta_twitter_card_ar')}}">

    <!-- favicons -->
    <link rel="shortcut icon" href="{{asset('home/favicon.png')}}">

    <!-- Style CSS -->
    <link rel="stylesheet" type="text/css" href="{{asset('home/fonts/jost/stylesheet.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('home/libs/line-awesome/css/line-awesome.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('home/libs/fontawesome-pro/css/fontawesome.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('home/libs/bootstrap/css/bootstrap-rtl.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('home/libs/slick/slick-theme.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('home/libs/slick/slick.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('home/libs/quilljs/css/quill.bubble.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('home/libs/quilljs/css/quill.core.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('home/libs/quilljs/css/quill.snow.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('home/libs/chosen/chosen.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('home/libs/datetimepicker/jquery.datetimepicker.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('home/libs/venobox/venobox.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/style.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('home/css/responsive.css')}}"/>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;600;700;800;900&display=swap"
          rel="stylesheet">

    <!-- jQuery -->
    <script src="{{asset('home/js/jquery-1.12.4.js')}}"></script>
    <script src="{{asset('home/libs/popper/popper.js')}}"></script>
    <script src="{{asset('home/libs/bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('home/libs/slick/slick.min.js')}}"></script>
    <script src="{{asset('home/libs/slick/jquery.zoom.min.js')}}"></script>
    <script src="{{asset('home/libs/isotope/isotope.pkgd.min.js')}}"></script>
    <script src="{{asset('home/libs/quilljs/js/quill.core.js')}}"></script>
    <script src="{{asset('home/libs/quilljs/js/quill.js')}}"></script>
    <script src="{{asset('home/libs/chosen/chosen.jquery.min.js')}}"></script>
    <script src="{{asset('home/libs/datetimepicker/jquery.datetimepopperpicker.full.min.js')}}"></script>
    <script src="{{asset('home/libs/venobox/venobox.min.js')}}"></script>
    <script src="{{asset('home/libs/waypoints/jquery.waypoints.min.js')}}"></script>
    <!-- orther script -->
    <script src="{{asset('home/js/main.js')}}"></script>

    <!-- Google Tag Manager -->
    <script>(function (w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start':
                    new Date().getTime(), event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-W4KNTBKT');</script>
    <!-- End Google Tag Manager -->
</head>

<body>
<div id="wrapper">
    <header id="header" class="site-header float-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-6 col-5">
                    <div class="site">
                        <div class="site__menu">
                            <a title="Menu Icon" href="#" class="site__menu__icon">
                                <i class="las la-bars la-24-black"></i>
                            </a>
                            <div class="popup-background"></div>
                            <div class="popup popup--left">
                                <a title="Close" href="#" class="popup__close">
                                    <i class="las la-times la-24-black"></i>
                                </a><!-- .popup__close -->
                                <div class="popup__content">

                                    <div class="popup__menu popup__box">
                                        <ul class="menu-arrow">
                                            <li>
                                                <a href="{{url('/')}}" title="الرئيسية">الرئيسية</a>
                                            </li>
                                            <li>
                                                <a href="#features" title="خدماتنا">خدماتنا</a>
                                            </li>
                                            <li>
                                                <a href="#about" title="لماذا نحن">لماذا نحن</a>
                                            </li>
                                            <li>
                                                <a href="#advantage" title="مميزاتنا">مميزاتنا</a>
                                            </li>
                                            <li>
                                                <a href="https://api.whatsapp.com/send?phone=966539811400&text=مرحبا, ما هو سعر اشتراك تاسكلو"
                                                   title="الأسعار">الأسعار</a>
                                            </li>
                                            {{--                                            <li>--}}
                                            {{--                                                <a href="#testemonial" title="أراء العملاء">أراء العملاء</a>--}}
                                            {{--                                            </li>--}}
                                            {{--                                            <li>--}}
                                            {{--                                                <a href="#blog" title="الأخبار">الأخبار</a>--}}
                                            {{--                                            </li>--}}
                                            <li>
                                                <a href="#contactus" title="تواصل معنا">تواصل معنا</a>
                                            </li>
                                        </ul>
                                    </div><!-- .popup__menu -->
                                </div><!-- .popup__content -->
                                <div class="popup__button popup__box">
                                    <a title="Add place"
                                       href="{{url('/admin')}}"
                                       class="btn">
                                        <span>تجربة مجانية</span>
                                    </a>
                                </div><!-- .popup__button -->
                            </div><!-- .popup -->
                        </div><!-- .site__menu -->
                        <div class="site__brand">
                            <a title="Logo" href="{{url('/')}}" class="site__brand__logo"><img
                                    src="{{asset('home/logo.png')}}" alt="Golo"></a>
                        </div><!-- .site__brand -->

                    </div><!-- .site -->
                </div><!-- .col-md-6 -->
                <div class="col-xl-6 col-7">
                    <div class="right-header align-right">
                        <nav class="main-menu">
                            <ul>
                                <li>
                                    <a href="{{url('/')}}" title="الرئيسية">الرئيسية</a>
                                </li>
                                <li>
                                    <a href="#features" title="خدماتنا">خدماتنا</a>
                                </li>
                                <li>
                                    <a href="#about" title="لماذا نحن">لماذا نحن</a>
                                </li>
                                <li>
                                    <a href="#advantage" title="مميزاتنا">مميزاتنا</a>
                                <li>
                                    <a href="https://api.whatsapp.com/send?phone=966539811400&text=مرحبا, ما هو سعر اشتراك تاسكلو"
                                       title="الأسعار">الأسعار</a>
                                </li>
                                </li>
                                {{--                                <li>--}}
                                {{--                                    <a href="#testemonial" title="راء العملاء">أراء العملاء</a>--}}
                                {{--                                </li>--}}
                                {{--                                <li>--}}
                                {{--                                    <a href="#blog" title="الأخبار">الأخبار</a>--}}
                                {{--                                </li>--}}

                                <li>
                                    <a href="#contactus" title="تواصل معنا">تواصل معنا</a>
                                </li>
                            </ul>
                        </nav>
                        <div class="right-header__button btn">
                            <a title="Add place"
                               href="{{url('/admin')}}">
                                <i class="las la-plus la-24-white"></i>
                                <span>تجربة مجانية</span>
                            </a>
                        </div><!-- .right-header__button -->
                    </div><!-- .right-header -->
                </div><!-- .col-md-6 -->
            </div><!-- .row -->
        </div><!-- .container-fluid -->
    </header><!-- .site-header -->

    <main id="main" class="site-main  offset-item">
        @yield('content')
    </main><!-- .site-main -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>

    <a href="https://api.whatsapp.com/send?phone=966539811400&text=مرحبا, أريد تجربة مجانية في تاسكلو"
       class="whatsapp-float" target="_blank">
        <i class="fab fa-whatsapp whatsapp-icon-float"></i>
    </a>
    <footer id="footer" class="footer">
        <div class="container">
            <div class="footer__top">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="footer__top__info">
                            <a title="Logo" href="{{url('/')}}" class="footer__top__info__logo"><img
                                    src="{{asset('home/logo.png')}}" alt="Golo"></a>
                            <p class="footer__top__info__desc">تاسكلو هو نظام إدارة مهام مبتكر مصمم خصيصًا لشركات
                                التسويق لتحسين كفاءة العمليات اليومية وضمان تقديم خدمة عملاء متميزة. نسعى لتسهيل إدارة
                                المهام وتبسيط العمل الجماعي من خلال توفير أدوات تساعد في تتبع أداء الموظفين، تنظيم
                                العمل، وتخصيص المهام بشكل ذكي وسريع.
                            </p>

                        </div>
                    </div>
                    <div class="col-lg-2">
                        <aside class="footer__top__nav">
                            <h3>عام</h3>
                            <ul>
                                <li>
                                    <a href="{{url('/')}}" title="الرئيسية">الرئيسية</a>
                                </li>
                                <li><a title="من نحن" href="#">من نحن</a></li>
                                {{--                                <li><a title="الأسئلة الشائعة" href="#faq">الأسئلة الشائعة</a></li>--}}
                                <li><a title="سياسة الخصوصية" href="{{route('privacy')}}">سياسة الخصوصية</a></li>
                            </ul>
                        </aside>
                    </div>
                    <div class="col-lg-2">

                    </div>
                    <div class="col-lg-3">
                        <aside class="footer__top__nav footer__top__nav--contact">
                            <h3>تواصل معنا</h3>
                            <p>البريد الإلكتروني: <a href="mailto:support@domain.com">info@intshar.net</a></p>
                            <div class="d-flex">
                                رقم الهاتف:
                                <p dir="ltr" class="mx-2"><a href="tel:+966539811400">+966539811400</a></p>
                            </div>
                            <ul>
                                <li class="facebook">
                                    <a title="Facebook" href="#">
                                        <i class="la la-facebook-f"></i>
                                    </a>
                                </li>
                                <li class="twitter">
                                    <a title="Twitter" href="#">
                                        <i class="la la-twitter"></i>
                                    </a>
                                </li>
                                <li class="youtube">
                                    <a title="Youtube" href="#">
                                        <i class="la la-youtube"></i>
                                    </a>
                                </li>
                                <li class="instagram">
                                    <a title="Instagram" href="#">
                                        <i class="la la-instagram"></i>
                                    </a>
                                </li>
                                <li class="instagram">
                                    <a title="Snapchat" href="#">
                                        <i class="la la-snapchat"></i>
                                    </a>
                                </li>
                            </ul>
                        </aside>
                    </div>
                </div>
            </div><!-- .top-footer -->
            <div class="footer__bottom">
                <p class="footer__bottom__copyright">{{Carbon::now()->year}} &copy; <a
                        title="قمة الإنتشار للتسويق الإلكتروني"
                        href="https://intshar.net">قمة الإنتشار للتسويق الإلكتروني</a>.
                    كل الحقوق محفوظة.</p>
            </div><!-- .top-footer -->
        </div><!-- .container -->
    </footer><!-- site-footer -->
</div><!-- #wrapper -->

<!-- Google Tag Manager (noscript) -->
<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-W4KNTBKT"
            height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->
</body>
</html>
