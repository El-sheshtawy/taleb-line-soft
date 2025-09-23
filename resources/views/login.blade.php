<!DOCTYPE html>

<html lang='en' dir="rtl">

<head>
<title>منصة طالب</title>

<link rel="shortcut icon" href="{{asset('/front/assets/images/footer-logo.png')}}" type="image/x-icon">
<link href="{{ asset('css/sweetalert2.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{asset('/front/dist/fonts/font-awesome-4.7.0/css/font-awesome.min.css')}}">
<link rel="stylesheet" href="{{asset('/front/dist/styles/style.rtlc164.css?v=9')}}">
<script src={{asset('/front/assets/js/jquery-3.5.1.min.js')}}></script>
	<style type="text/css">
*{outline-color:var(--ut-focus-outline-color,-webkit-focus-ring-color)}.t-Login-container{-webkit-padding-start:var(--ut-grid-gutter-width,.5rem);-webkit-padding-end:var(--ut-grid-gutter-width,.5rem);display:grid;gap:var(--ut-login-container-item-spacing,1rem);grid-area:body;grid-template-areas:"login-header" "login-body" "login-footer";grid-template-columns:1fr;grid-template-rows:auto 1fr auto;padding-inline-end:var(--ut-grid-gutter-width,.5rem);padding-inline-start:var(--ut-grid-gutter-width,.5rem);position:relative}.container{-webkit-margin-start:auto;-webkit-margin-end:auto;margin-inline-end:auto;margin-inline-start:auto;width:100%}.row{-webkit-margin-end:calc(var(--ut-grid-gutter-width, .5rem)*-1);-webkit-margin-start:calc(var(--ut-grid-gutter-width, .5rem)*-1);margin-inline-end:calc(var(--ut-grid-gutter-width, .5rem)*-1);margin-inline-start:calc(var(--ut-grid-gutter-width, .5rem)*-1)}.row{display:flex;flex-wrap:wrap}.row .col.col-end{-webkit-padding-end:var(--ut-grid-gutter-width,8px);padding-inline-end:var(--ut-grid-gutter-width,8px)}.row .col.col-start{-webkit-padding-start:var(--ut-grid-gutter-width,8px);padding-inline-start:var(--ut-grid-gutter-width,8px)}.col.col-end{-webkit-padding-end:0;padding-inline-end:0}.col.col-start{-webkit-padding-start:0;padding-inline-start:0}.col-1,.col-10,.col-11,.col-12,.col-2,.col-3,.col-4,.col-5,.col-6,.col-7,.col-8,.col-9{flex-basis:100%;max-width:100%}.col-12{flex-basis:100%;max-width:100%}.col{-webkit-padding-start:var(--ut-grid-gutter-width,.5rem);-webkit-padding-end:var(--ut-grid-gutter-width,.5rem);flex-basis:0;flex-grow:1;max-width:100%;padding-inline-end:var(--ut-grid-gutter-width,.5rem);padding-inline-start:var(--ut-grid-gutter-width,.5rem);position:relative;width:100%}.t-Form--stretchInputs{--ut-smart-filter-max-width:100%}.t-Login-region{-webkit-animation:loginFade ease-out 1;animation:loginFade ease-out 1;-webkit-animation-duration:.35s;animation-duration:.35s;-webkit-animation-fill-mode:forwards;animation-fill-mode:forwards}.t-Login-region{--ut-field-padding-x:0rem;--ut-field-padding-y:1rem;--ut-field-input-icon-padding-y:.75rem;--ut-field-input-icon-padding-x:.75rem;-webkit-margin-start:auto;-webkit-margin-end:auto;-webkit-backdrop-filter:var(--ut-login-region-filter);backdrop-filter:var(--ut-login-region-filter);background-clip:padding-box;background-color:var(--ut-login-region-background-color,var(--ut-component-background-color));border-color:var(--ut-login-region-border-color,var(--ut-component-border-color));border-radius:var(--ut-login-region-border-radius,.5rem);border-style:solid;border-width:var(--ut-login-region-border-width,var(--ut-component-border-width,1px));box-shadow:var(--ut-login-region-box-shadow,var(--ut-component-box-shadow));margin-inline-end:auto;margin-inline-start:auto;max-width:100%;padding:var(--ut-login-region-padding,2rem);transform-origin:center;width:var(--ut-login-region-max-width,28.75rem)}.t-Login-logo{background-color:var(--ut-app-icon-background-color,var(--ut-component-icon-background-color));background-position:50%;background-repeat:no-repeat;background-size:cover;border-radius:var(--ut-login-logo-border-radius,.25rem);color:var(--ut-app-icon-color,var(--ut-component-icon-color));display:block;font-size:var(--ut-login-logo-font-size,2rem);height:var(--ut-login-logo-size,4rem);line-height:var(--ut-login-logo-size,4rem);margin:0 auto 1rem;-o-object-fit:cover;object-fit:cover;text-align:center;width:var(--ut-login-logo-size,4rem)}.t-Login-title{font-size:1.5rem;font-weight:var(--a-base-font-weight-semibold,500);margin:1rem 0;text-align:center}.t-Login-body{--a-icon-padding:.75rem;--a-icon-size:1rem;--a-field-input-font-size:1rem;--a-field-input-padding-x:.5rem}.t-Form--stretchInputs .t-Form-fieldContainer,.t-Form-fieldContainer--stretchInputs{--a-field-input-flex-grow:1}.t-Form-fieldContainer.rel-col{flex-direction:column;float:none!important}.t-Form--labelsAbove .t-Form-fieldContainer,.t-Form-fieldContainer--stacked{flex-direction:column}.col .rel-col{flex-basis:auto;max-width:100%}.apex-item-wrapper--color-picker,.apex-item-wrapper--has-icon{--ut-field-input-icon-offset:calc(var(--ut-field-input-icon-size, var(--a-icon-size, 1rem)) + var(--ut-field-input-icon-padding-x, .25rem) + var(--ut-field-input-icon-padding-x, .25rem));--ut-field-input-min-height:calc(var(--ut-field-input-icon-size, var(--a-icon-size, 1rem)) + var(--ut-field-input-icon-padding-y, .25rem) + var(--ut-field-input-icon-padding-y, .25rem));--ut-field-input-padding-x-offset:calc(var(--ut-field-input-icon-offset, 1.5rem) - var(--a-field-input-border-width, 1px))}.t-Form-fieldContainer{--a-field-input-font-size:var(--ut-xs-field-input-font-size,1rem);--a-field-input-line-height:var(--ut-xs-field-input-line-height)}.t-Form-fieldContainer{clear:both;display:flex}.t-Form--labelsAbove .t-Form-fieldContainer.rel-col>.col,.t-Form-fieldContainer--stacked.rel-col>.col{flex-basis:auto;max-width:100%}.t-Form-fieldContainer.rel-col>.col{flex-basis:100%;max-width:100%}.t-Form--labelsAbove .t-Form-fieldContainer .t-Form-labelContainer,.t-Form-fieldContainer--stacked .t-Form-labelContainer{-webkit-padding-after:0;padding-block-end:0;text-align:start}.t-Form-labelContainer--hiddenLabel.col-0,.t-Form-labelContainer--hiddenLabel.col-null{--ut-field-padding-y:0;--ut-field-padding-x:0;flex-basis:0%;max-width:0}.t-Form-labelContainer.col-null,.u-Form-inputContainer.col-null{flex-basis:25%;max-width:25%}.t-Form-labelContainer--hiddenLabel{padding:0}.t-Form-labelContainer{-webkit-padding-after:0;padding-block-end:0;text-align:start}.t-Form-labelContainer{-webkit-padding-before:var(--ut-field-padding-y,.5rem);-webkit-padding-start:var(--ut-field-padding-x,.5rem);-webkit-padding-end:var(--ut-field-padding-x,.5rem);-webkit-padding-after:var(--ut-field-padding-y,.5rem);padding-block-end:var(--ut-field-padding-y,.5rem);padding-block-start:var(--ut-field-padding-y,.5rem);padding-inline-end:var(--ut-field-padding-x,.5rem);padding-inline-start:var(--ut-field-padding-x,.5rem);text-align:end}.t-Form-label{-webkit-padding-before:var(--ut-field-label-padding-y,.25rem);-webkit-padding-after:var(--ut-field-label-padding-y,.25rem);color:var(--ut-field-label-text-color);display:inline-block;font-size:var(--ut-field-label-font-size,.75rem);font-weight:var(--ut-field-label-font-weight);-webkit-hyphens:auto;hyphens:auto;line-height:var(--ut-field-label-line-height,1rem);padding-block-end:var(--ut-field-label-padding-y,.25rem);padding-block-start:var(--ut-field-label-padding-y,.25rem)}.t-Form-fieldContainer--hiddenLabel>.t-Form-inputContainer.col-null{flex-basis:100%;max-width:100%}.t-Form--labelsAbove .t-Form-fieldContainer .t-Form-inputContainer,.t-Form-fieldContainer--stacked .t-Form-inputContainer{-webkit-padding-before:0;padding-block-start:0}.t-Form-inputContainer.col-null,.u-Form-inputContainer.col-null{flex-basis:75%;max-width:75%}.t-Form-inputContainer{-webkit-padding-before:0;padding-block-start:0}.t-Form-inputContainer{-webkit-padding-before:var(--ut-field-padding-y,.5rem);-webkit-padding-start:var(--ut-field-padding-x,.5rem);-webkit-padding-end:var(--ut-field-padding-x,.5rem);-webkit-padding-after:var(--ut-field-padding-y,.5rem);padding-block-end:var(--ut-field-padding-y,.5rem);padding-block-start:var(--ut-field-padding-y,.5rem);padding-inline-end:var(--ut-field-padding-x,.5rem);padding-inline-start:var(--ut-field-padding-x,.5rem);position:relative}.t-Form-itemWrapper{align-items:flex-start;display:flex;flex-wrap:nowrap;min-height:var(--ut-field-item-min-height,calc(var(--ut-field-label-line-height, 1rem) + var(--ut-field-label-padding-y, .25rem)*2))}.a-Form-error,.t-Form-error{-webkit-margin-before:var(--ut-field-error-margin-y,var(--ut-field-assistance-margin-y,.25rem));display:block;font-size:var(--ut-field-error-font-size,var(--ut-field-assistance-font-size,.6875rem));line-height:var(--ut-field-error-line-height,var(--ut-field-assistance-line-height,1rem));margin-block-start:var(--ut-field-error-margin-y,var(--ut-field-assistance-margin-y,.25rem))}.apex-item-wrapper--checkbox,.apex-item-wrapper--display-only,.apex-item-wrapper--geocoded-address,.apex-item-wrapper--list-manager,.apex-item-wrapper--radiogroup,.apex-item-wrapper--shuttle,.apex-item-wrapper--single-checkbox,.apex-item-wrapper--yes-no{--ut-prepost-background-color:transparent;--ut-prepost-border-color:transparent}.t-Form-itemWrapper .a-Switch,.t-Form-itemWrapper .apex-item-group,.t-Form-itemWrapper .apex-item-icon,.t-Form-itemWrapper .apex-item-markdown-editor,.t-Form-itemWrapper .apex-item-single-checkbox,.t-Form-itemWrapper .ck-editor,.t-Form-itemWrapper .mapboxgl-map,.t-Form-itemWrapper a-autocomplete,.t-Form-itemWrapper a-date-picker,.t-Form-itemWrapper fieldset{order:2}.apex-item-single-checkbox{align-self:center;display:flex}.t-Login-buttons{--a-button-padding-y:1rem;--a-button-padding-x:1.5rem;--a-button-font-size:1rem;--a-button-line-height:1rem}.t-Login-buttons .t-Button{width:100%}.t-Button--hot{--a-button-font-weight:var(--a-base-font-weight-bold,700)}.a-Button,.a-CardView-button,.t-Button,.t-Form-helpButton,.ui-button{transition:background-color .2s ease,border-color .2s ease,box-shadow .2s ease,color .2s ease}.apex-item-file--native::-webkit-file-upload-button,.apex-item-file--native::-webkit-file-upload-button:active,.apex-item-file--native::-webkit-file-upload-button:focus,.apex-item-file--native::-webkit-file-upload-button:hover,.t-Button,.t-Button:active,.t-Button:focus,.t-Button:hover,.t-Form-helpButton,.t-Form-helpButton:active,.t-Form-helpButton:focus,.t-Form-helpButton:hover{text-decoration:none}.apex-item-file--native::-webkit-file-upload-button,.t-Button,.t-Form-helpButton{-webkit-padding-before:calc(var(--a-button-padding-y, .5rem) - var(--a-button-border-width, 1px));-webkit-padding-after:calc(var(--a-button-padding-y, .5rem) - var(--a-button-border-width, 1px));-webkit-padding-start:calc(var(--a-button-padding-x, .75rem) - var(--a-button-border-width, 1px));-webkit-padding-end:calc(var(--a-button-padding-x, .75rem) - var(--a-button-border-width, 1px));align-items:center;-webkit-appearance:none;appearance:none;border-radius:var(--a-button-border-radius,.125rem);border-style:solid;border-width:var(--a-button-border-width,1px);cursor:var(--a-button-cursor,pointer);display:inline-block;font-family:inherit;font-size:var(--a-button-font-size,.75rem);font-weight:var(--a-button-font-weight,400);justify-content:center;line-height:var(--a-button-line-height,1rem);margin:0;padding-block-end:calc(var(--a-button-padding-y, .5rem) - var(--a-button-border-width, 1px));padding-block-start:calc(var(--a-button-padding-y, .5rem) - var(--a-button-border-width, 1px));padding-inline-end:calc(var(--a-button-padding-x, .75rem) - var(--a-button-border-width, 1px));padding-inline-start:calc(var(--a-button-padding-x, .75rem) - var(--a-button-border-width, 1px));position:relative;text-align:center;text-shadow:var(--a-button-text-shadow,none);-webkit-user-select:none;user-select:none;white-space:nowrap;will-change:background-color,border-color,box-shadow,color,padding,font-size;z-index:var(--a-button-zindex)}

        .login-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 90%;
            max-width: 400px;
        }

        </style>




</head>


<body class='rtl' style="">

<header class="header-top lg-header  ">
    <nav class=" justify-content-between navbar navbar-expand-lg px-0
             container-fluid px-2 px-md-4 pt-md-4">
        <a class="navbar-brand pr-0 pr-md-3" href="../eg-ar.html">
                  <span class="d-none d-lg-block">
                        <img src="{{asset('/front/dist/images/mob-logo.png')}}" alt=" " class="logo img-fluid " height="425" width="425">
                    </span>
            <span class="d-block d-lg-none">
                        <img src="{{asset('/front/dist/images/mob-logo.png')}}" alt=" " class="logo img-fluid " style="width: auto">
                    </span>
        </a>

                    <a href="..\eg-ar.html" class="d-lg-none d-flex btn btn-sec-border px-4 " style="width: 37px; color: #FFFF00; background-color: #79121C;"> الصفحة الرئيسية</a>

        
                                <a class="px-2 " id="lang-bar" style="color: #ffff" href="" > En</a>
                        <span style="color: #ffff">|</span>
                                    <a class="px-2 active" id="lang-bar" style="color: #ffff" href="../eg-ar.html" > Ar</a>
                        <span style="color: #02b186"></span>
        
        <div class="nav allNav px-3 py-md-0 py-3 d-flex flex-grow-1 flex-md-row-reverse justify-content-md-between" id="navbarText">
            <div class="user-side align-items-start  navbar-nav ">
                                    <a class="p-1 px-2 register btn  btn-sec  ml-2" href="..\eg-ar.html" style="color: #FFFF00; background-color: #79121C"> الصفحة الرئيسية
                    </a>

            </div>

            <ul class="align-items-center justify-content-between navbar-nav ">
<!--  الرئيسية   -->                                                                                                                                                                        
                <li class="nav-item   ">
                    <a class="nav-link " href="../eg-ar.html" style="right: 0px; top: 0px"  > الرئيسية</a>
                    <div class="p-0 dropdown-menu main text-center overflow-hidden">
                        <ul class="sub-menu p-0 text-left text-white">   </ul>
                    </div>
                </li>
<!--  عن المنصة   -->
                  <li class="nav-item   ">
						 <a class="nav-link " href="about-us.html"   >	عن <span lang="ar-kw"><strong>المنصة</strong></span></a>
                      <div class="p-0 dropdown-menu main text-center overflow-hidden">
                        <ul class="sub-menu p-0 text-left text-white">  </ul>
                    </div>
                </li>
<!--  الفيديوهات   -->
                <li class="nav-item   ">
                      <a class="nav-link " href="../eg-ar/video.html"  > <strong>الفيديوهات</strong></a>                                                        
                      <div class="p-0 dropdown-menu main text-center overflow-hidden">
                        <ul class="sub-menu p-0 text-left text-white">   </ul>
                        </div>
                    </li>
<!--  ??   -->                                                                                                                                                                                            
                     <li class="nav-item   ">
                    <a class="nav-link " href="mediaPage/music.html"  >   &nbsp;</a>
                    <div class="p-0 dropdown-menu main text-center overflow-hidden">
                        <ul class="sub-menu p-0 text-left text-white">  </ul>
                    </div>
                </li>
<!--  تواصل معنا   -->     
<li class="nav-item   ">
                  <a class="nav-link "  style="color: #FFFF00">صفحة تسجيل الدخول  </a>
                    <div class="p-0 dropdown-menu main text-center overflow-hidden">
                        <ul class="sub-menu p-0 text-left text-white">    </ul>
                    </div>
                </li>
<!--  ???   -->                                                                                                                                  
					<li class="nav-item   ">
                      <a class="nav-link " href="../eg-en/arabic-courses.html"  >&nbsp;</a>
                      <div class="p-0 dropdown-menu main text-center overflow-hidden">
                        <ul class="sub-menu p-0 text-left text-white">   </ul>
                        </div>
                    </li>
   </ul>
            
        </div>
        <span class="d-block d-lg-none js-nav-toggle nav-toggle text-white"><i></i> </span>
    </nav>
    <div class="menuclose" style="right: 0; top: 0"></div>
</header>

    <div class='page-container'>

            <form action="{{route('login')}}" method="post" name="wwv_flow" id="wwvFlowForm" data-oj-binding-provider="none" novalidate="" autocomplete="off">
            	@csrf
            	<div class="text-center">
            		<br>
                    <div class="container sm-container  p-3 p-md-5 ">
            
                        <div class="text-center page-header">
            
                            <h2 class="sec-title  mb-4" style="background-color: #FFFF00; radius-0 ;height: 44px; font-size: x-large;">
            				دخول المستخدمين</h2>
                        </div>
            
            
                        <div class="text-center alert alert-success" id="success" style="display:none">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
            
                            <p id="success-message"></p>
                        </div>
            
                        <div class='text-center alert alert-danger ' id="warning" style="display: none">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><i class="fas fa-times"></i></button>
                           <i class="fas fa-times"></i>
                           <div id="errors-list">
                                           </div>
            
                        </div>
            
            
                        <div class="text-center alert alert-danger" id="error" style="display: none">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
            
                           <p id="error-message"></p>
                        </div>
            
                            <div class="form-group">
                                
            
                                <input id="username" class="form-control" required placeholder="اسم المستخدم*" name="username" type="text">
                            </div>
                            <div class="form-group">
                                <input id="mail" class="form-control" required placeholder="كلمة المرور*" name="password" type="password">
                            </div>
                             
                            <button class="btn btn-sec w-100 lg-btn radius-0 get-in-touch-submit" type="submit"> تسجيل الدخول </button>
            
                    </div>
            	</div>
            </form>

      </div>
                    <div class="py-3  px-lg-4 d-flex justify-content-md-between flex-wrap justify-content-center text-center">
                        <span class="font-weight-bold"> ليس لديك حساب؟   <a href="https://line-soft.com" class="bold text-sec my-1 ml-2">تواصل معنا</a> </span>

                    </div>
     <!-- الذيل -->


 <footer class="">
    <div class="top px-3">
        <div class="container ">
            <div class="align-items-center flex-column-reverse flex-md-row row">
                <div class="col-md-2 py-1 ">
                    <div class="social d-flex justify-content-center align-items-center ">
                        <a class='ico mx-1' href="https://iwtsp.com/96550602038">  
                            <i class="fa fa-whatsapp"></i> 
                        </a>
                      <a class='ico mx-1' href="https://www.facebook.com/">  
					    <i class="fa fa-facebook"></i> 
					</a>
                        <a class='ico mx-1' href="https://www.youtube.com/">  
                            <i class="fa fa-youtube"></i> 
                        </a>
                    </div>
                </div>
                <div class="col-md-8 py-1 ">
                    <ul class="d-flex flex-wrap align-items-center justify-content-center links list-inline mb-0 ">
                        <!-- يمكن إضافة روابط هنا -->
                    </ul>
                </div>
                <div class="col-md-2 d-inline-flex justify-content-center py-1 ">
                    <a href="../index.html">
                        <img src="{{asset('/front/dist/images/tlogo.png')}}" alt=" " class="logo img-fluid " ></a><a class="navbar-brand" href="index.html">
                        </a>
                </div>
            </div>
        </div>
    </div>
    <div class="bottom py-2 py-md-4">
        <div class="text-center">
            <span>
                <a class="py-1" href='ar/pages/conditions_policy.html'>الشروط و الأحكام</a> /
                <a class="py-1" href='ar/pages/conditions_policy.html'>سياسة الخصوصية</a>
                <br>
                <a class="py-1" href="https://line-soft.com" target="_blank">جميع الحقوق محفوظة © 2025 line-soft.com</a>
            </span>
        </div>
    </div>
</footer>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.min.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ في الإدخال',
                    html: '{!! implode("<br>", $errors->all()) !!}',
                });
            @endif

            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'نجاح!',
                    text: '{{ session("success") }}',
                    confirmButtonText: 'حسناً'
                });
            @endif
    
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ!',
                    text: '{{ session("error") }}',
                    confirmButtonText: 'حسناً'
                });
            @endif
    
            @if(session('warning'))
                Swal.fire({
                    icon: 'warning',
                    title: 'تحذير!',
                    text: '{{ session("warning") }}',
                    confirmButtonText: 'حسناً'
                });
            @endif
        });
    </script>



</body>
</html>