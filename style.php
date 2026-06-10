<?php 
if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    header("Location: index.php");
    exit;
}
?>
<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/gh/rizmyabdulla/fontawesome-pro@main/releases/v7.2.0/css/fontawesome.css"
/>
<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/gh/rizmyabdulla/fontawesome-pro@main/releases/v7.2.0/css/duotone.css"
/>
<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/gh/rizmyabdulla/fontawesome-pro@main/releases/v7.2.0/css/brands.css"
/>
<style>
/* Variables */
:root {
    --bg: #FFDCDC;
    --container: #FFF2EB;
    --window: #FFF2EB;
    --button: #FFE8CD;
    --active: #FFD6BA;
    --text: #000;
}

/* Default Settings */
* {
    /*box-shadow: inset 0 0 0 1px black !important;*/
    margin: 0;
    user-select: none;
    scrollbar-width: none;
    font-family: Sans-serif;
    transition: .15s ease-in;
    animation-duration: 0.5s;
    animation-iteration-count: infinite;
}
html {
    height: 100%;
}
body { 
    background: var(--bg);
    min-height: 100%;
}
div:not(#inForm) a {
    text-decoration: none;
}

/* Grid Layout */
.grid {
    display: grid;
    gap: 24px;
    padding: 0 28px 52px;
}
.overviewpage {
    grid-template-areas: 'title' 'balance' 'quick_action' 'goal' 'transaction' 'wishlist' 'copyright' 'nav';
    grid-template-rows: 60px auto auto auto auto auto auto;
}
.transactionpage {
    grid-template-areas: 'title' 'sortfilter' 'transaction' 'copyright' 'nav';
    grid-template-rows: 60px auto auto auto;
}
.wishlistpage {
    grid-template-areas: 'title' 'goal' 'sortfilter' 'wishlist' 'copyright' 'nav';
    grid-template-rows: 60px auto auto auto auto;
}
.profilepage {
    grid-template-areas: 'title title' 'photo name' 'info info' 'stats stats' 'logout logout' 'copyright copyright' 'nav nav';
    grid-template-rows: 56px 108px auto auto auto auto;
    grid-template-columns: 108px 2fr !important;
}

@media (min-width: 720px) {
    .grid {
        padding: 0 48px 52px;
        grid-template-columns: 3fr 3fr;
    }
    .overviewpage {
        grid-template-areas: 'title title' 'balance goal' 'quick_action goal' 'transaction wishlist' 'copyright copyright' 'nav nav';
        grid-template-rows: 60px auto auto auto auto;
    }
    .transactionpage {
        grid-template-areas: 'title title' 'sortfilter sortfilter' 'transaction transaction' 'copyright copyright' 'nav nav';
        grid-template-rows: 60px auto auto auto;
    }
    .wishlistpage {
        grid-template-areas: 'title title' 'goal goal' 'sortfilter sortfilter' 'wishlist wishlist' 'copyright copyright' 'nav nav';
        grid-template-rows: 60px auto auto auto auto;
    }
    h1#title {
        margin: 0 -48px;
    }
    div#nav {
        margin: 0 -48px;
    }
    .overviewpage div#goal h3 {
        line-height: 150px;
    }
    .wishlistpage div:not(#goal) div.list , .transactionpage div.list {
        width: calc(50% - 3px - 24px);
    }
    .wishlistpage .even , .transactionpage .even {
        margin-left: 48px;
    }
    .wishlistpage .Slist , .transactionpage .Slist {
        border-top: 0 !important;
    }
    #addTRForm , #addWLForm , #about {
        grid-column: 1 / span 2;
    } 
}

/* Shared */
#copyright {
    grid-area: copyright;
    text-align: center;
    font-size: 72%;
}
#title {
    grid-area: title;
    position: sticky;
    background: white;
    top: 0;
    z-index: 3;
    border-radius: 0;
    margin: 0 -28px;
    text-wrap: nowrap;
    overflow: hidden;
}
#title p {
    overflow: hidden;
    line-height: 40px;
}
#nav {
    grid-area: nav;
    position: fixed;
    width: 100%; height: 52px;
    bottom: 0;
    z-index: 2;
    display: flex;
    margin: 0 -28px;
    background: var(--window);
}
#nav nav {
    flex: 3;
    position: relative;
}
#nav nav i {
    position: relative;
    bottom: 6px;
    --fa-primary-color: #505050; 
    --fa-secondary-color: #505050;
    filter: saturate(3);
}
.active {
    --fa-primary-color: #FFD6BA !important;
    --fa-secondary-color: #FFD6BA !important;
}
#nav nav span {
    position: absolute;
    bottom: 5px;
    font-size: 68%;
}
#quick_action div {
    margin-top: 10px;
    display: flex;
    height: 72px;
    border-radius: 16px;
    overflow: hidden;
    margin-bottom: 4px;
}
#quick_action {
    grid-area: quick_action;
}
#quick_action input:checked + label {
    background: var(--active);
}
#balance {
    grid-area: balance;
}
#hidballab {
    cursor: pointer;
}
.window:not(#photo , #name) {
    background: var(--window);
    padding: 12px 16px;
}
#balance span , .window:not(#inForm) a , #info span , #stats span {
    position: absolute;
    right: 16px;
    margin-top: 2px;
}
#bal {
    background: var(--window);
}
#nav * , #quick_action label {
    display: flex;
    justify-content: center;
    align-items: center;
}
#nav label , #quick_action label {
    flex: 2;
}
button , input , #quick_action label {
    background: var(--button);
}
input[type=checkbox] {
    display: none !important; 
}
.window {
    border-radius: 16px;
    position: relative;
}
.list {
    display: inline-block;
    padding: 10px 0;
    width: 100%;
}
.window:has(.list) {
    padding: 12px 16px 0 !important;
}
table {
    border-spacing: 0
}
form.window tr {
    height: 36px;
}
tr td:first-child {
    padding-right: 12px;
}
.noResult {
    text-align: center;
    line-height: 50px;
}

/* About */
.aboutpage h1 {
    font-size: 320%;
    text-align: center;
    padding: 180px 24px;
}
.aboutpage h1 button {
    font-size: 32%;
}
#about {
    padding: 80px 24px;
    background: var(--window);
}

/* Login & Register */
#inForm {
    position: sticky;
    top: 18%;
    margin: 0 24px;
}
#inForm table {
    width: 100%;
}
#inForm h1 {
    padding: 16px 8px;
    margin: -12px -16px 0;
    text-align: center;
    background: var(--active);
    border-radius: 16px 16px 0 0;
}
#inForm p {
    font-size: 92%;
    text-align: center;
}
#inForm td {
    display: inline-block;
}
#inForm .errMsg {
    display: inline;
    font-size: 80%;
    color: red;
    text-align: left;
}
.spanCheck {
    font-size: 86%;
    position: absolute;
}
.spanCheck span {
    display: inline-block;
    width: 18px; height: 18px;
    font-size: 160%;
    border-radius: 50%;
    background: var(--button);
    vertical-align: middle;
    line-height: 13px;
    text-indent: 2px;
    margin: 5px 6px;
}
.submit {
    text-align: right;
}
section {
    position: absolute;
    bottom: 3%;
    width: 100%;
    text-align: center;
    font-size: 72%;
}

/* Transaction */
#transaction {
    grid-area: transaction;
}
.t1 { color: green; }
.t-1 { color: red; }
#addTRForm {
    left: 0;
    position: fixed;
    bottom: 72px;
    margin: 0 24px;
}
#addTRForm label { 
    display: inline-block;
    border-radius: 10px;
    padding: 4px 8px; 
    background: var(--button);
}
.list:not(.Flist) {
    border-top: 1px solid;
}

/* Wishlist */
#goal {
    grid-area: goal;
}
#wishlist {
    grid-area: wishlist;
}
#addWLForm {
    left: 0;
    position: fixed;
    bottom: 72px;
    margin: 0 24px;
}
button , input , select {
    border: none;
    outline: none;
    border-radius: 1px;
}
form td + td * {
    background: var(--window);
    transition: 0s;
    appearance: none;
}
form:not([inert]) td + td *:not(label , span) {
    border-bottom: 1px dashed black;
    appearance: auto;
}
form:not([inert]) td + td *:focus {
    border-bottom: 1px solid black;
}
.wishlistpage button , .wishlistpage .window:not(#addWLForm) input[type=submit] , #sortfilter label {
    margin: 7px 2px 0 0 ;
}
b , #hidballab h3 {
    display: inline-block;
    width: max-content;
}
tr {
    height: 24px;
}
td + td:not(:has(label)) , td + td *:not(label) {
    width: 100%;
    text-align: right; 
}
.list:has(form[inert]) .action div {
    display: none;
}
.overviewpage .action {
    display: none;
}
.full { color: orange; }

/* Sort & Filter */
#sortfilter {
    grid-area: sortfilter;
}
#sortfilter label {
    display: inline-block;
    background: var(--button);
    font-size: 86%;
}
#quick_action input:hover + label , #sortfilter input + label:hover , #sortfilter select:hover , input[type=submit]:hover , button:hover , i:not(#disabled, .fa-pencil):hover {
    opacity: .8;
}
#sortfilter input:checked + label {
    background: var(--active);
}
#sortfilter select {
    padding: 3px;
    background: var(--button);
    border-radius: 8px;
    width: calc(100% - 65px);
}
body:has(#TRbutton:checked , #WLbutton:checked , #uploadPP:checked) .grid div:not(#nav , #quick_action , .submit) :not(label) {
    filter: blur(1px);
    pointer-events: none;
}
body:has(#TRbutton:checked , #WLbutton:checked , #uploadPP:checked) {
    overflow: hidden;
}
input[type=checkbox]:not(#WLbutton , #TRbutton):checked + label:not(.spanCheck) , input[type=checkbox]:checked + .spanCheck span , input[type=radio]:checked + label {
    background: var(--active) !important;
}
body:has(#all:checked) .window:not(#sortfilter) label[for='all'] , body:not(:has(#sortfilter)) label[for='all'] {
    display: none;
}
.window:not(#sortfilter) label[for='all'] {
    box-shadow: none !important;
    font-size: 10px;
    color: #0000EE;
    text-decoration: underline;
}
/* Profile */
#photo {
    grid-area: photo;
    display: inline-block;
    border: solid 4px white;
    border-radius: 50%;
    overflow: hidden;
}
#photo img {
    position: relative;
    width: auto;
    height: auto;
}
#photo h3 {
    width: 100px;
    height: 100px;
    line-height: 100px;
}
#name {
    grid-area: name;
    text-wrap: nowrap;
}
#name:has(input[inert]) {
    overflow: hidden;
}
#info {
    grid-area: info;
}
.profilepage b , .profilepage li {
    line-height: 1.4;
}
.profilepage form input[type=text] , .profilepage form div {
    width: 100%;
    max-width: calc(100%);
    overflow: hidden;
    line-height: 38px;
    font-size: 32px;
    background: var(--bg);
    margin: 18px 0 8px;
    padding: 0;
    border-bottom: 1px dashed black;
}
.profilepage form div i {
    position: absolute;
    right: 0px;
    padding: 7px 0 7px 40px;
    background-image: linear-gradient(to right, rgba(255,0,0,0), var(--bg), var(--bg));
    font-size: 22px;
}
.profilepage #name form input:not([inert]) {
    border-bottom: 1px solid black;
    display: block;
}
.profilepage input:not([inert]) ~ div {
    display: none;
}
input[inert] ~ button {
    display: none;
}
#bal {
    field-sizing: content;
}
#stats {
    grid-area: stats;
}
#logout {
    grid-area: logout;
}
#logout label {
    font-size: 86%;
    display: inline-block;
    background: var(--button);
}
b + span {
    font-size: 92%;
}

/* Photo Upload */
#uploadForm {
    position: fixed;
    z-index: 1;
    top: 50%;
    left: 50%;
    translate: -50% -50%;
    width: 226px;
    padding: 24px !important;
}
#uploadForm label {
    display: inline-block;
    width: 200px;
    height: 200px;
    border-radius: 50%;
    overflow: hidden;
}
#uploadForm label img {
    position: relative;
    width: auto;
    height: auto;
}
#uploadForm label div , #photo h3 {
    position: absolute;
    z-index: 1;
    text-align: center;
    opacity: 0;
    border-radius: 50%;
    color: white;
    backdrop-filter: blur(4px);
    cursor: pointer;
    box-shadow: 0 0 0 4px var(--active);
    text-shadow: 0 2px 5px #00000090;
}
#uploadForm label div {
    width: 200px;
    height: 200px;
    line-height: 174px;
}
#uploadForm label div p {
    line-height: 1.3;
    margin-top: -72px;
    font-size: 90%;
}
#uploadForm label div:hover , #photo h3:hover {
    opacity: 1;
}
#uploadForm span {
    display: flex;
    justify-content: space-between;
}
input[type=range] {
    margin: 10px 0;
    position: relative;
}
#x {
    width: 200px;
}
#y {
    position: fixed;
    height: 200px;
    margin-left: 10px;
    margin-bottom: 0;
    writing-mode: vertical-lr;
}
#zoom {
    width: 100%;
}
body:has(#uploadPP:checked) #cce {
    position: absolute;
    z-index: 1;
    width: 100%; height: 100%;
}

/* Else idk */
button , input[type=submit] , #sortfilter label , #logout label {
    border-radius: 10px;
    padding: 5px 10px; 
}
button , input[type=submit] , #sortfilter select , .window:not(#quick_action , #nav) label:not(#hidballab , label[for=mag] , .spanCheck) , .window:not(#name) , #quick_action div , #nav , .spanCheck span {
    box-shadow: 0 0 6px #00000050;
}
#nav nav , input[type=checkbox] + label , input[type=radio] + label , button , input[type=submit] , select , #name i {
    cursor: pointer;
}
form:not([inert]) td + td * {
    text-align: left;
}
br {
    line-height: 12px;
}
button:active , a#active , [type=submit] {
    background: var(--active);
}
.notif {
    position: fixed;
    width: calc(100% - 54px);
    top: -48px;
    left: 0;
    padding: 8px 12px;
    margin: 0 16px;
    border-radius: 20px;
    z-index: 2;
    background: var(--button);
    animation: notif 1.6s .2s alternate 2;
    box-shadow: 0 0 8px #00000040;
    cursor: pointer;
}
@keyframes notif {
    36% { top: 70px; }
    to { top: 70px; }
}
i:not(.fa-plus) {
    text-shadow: 0 0 6px #00000050;
}
</style>