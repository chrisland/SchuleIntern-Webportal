(function(e){function t(t){for(var r,i,o=t[0],l=t[1],c=t[2],m=0,f=[];m<o.length;m++)i=o[m],Object.prototype.hasOwnProperty.call(n,i)&&n[i]&&f.push(n[i][0]),n[i]=0;for(r in l)Object.prototype.hasOwnProperty.call(l,r)&&(e[r]=l[r]);d&&d(t);while(f.length)f.shift()();return s.push.apply(s,c||[]),a()}function a(){for(var e,t=0;t<s.length;t++){for(var a=s[t],r=!0,o=1;o<a.length;o++){var l=a[o];0!==n[l]&&(r=!1)}r&&(s.splice(t--,1),e=i(i.s=a[0]))}return e}var r={},n={app:0},s=[];function i(t){if(r[t])return r[t].exports;var a=r[t]={i:t,l:!1,exports:{}};return e[t].call(a.exports,a,a.exports,i),a.l=!0,a.exports}i.m=e,i.c=r,i.d=function(e,t,a){i.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:a})},i.r=function(e){"undefined"!==typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},i.t=function(e,t){if(1&t&&(e=i(e)),8&t)return e;if(4&t&&"object"===typeof e&&e&&e.__esModule)return e;var a=Object.create(null);if(i.r(a),Object.defineProperty(a,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var r in e)i.d(a,r,function(t){return e[t]}.bind(null,r));return a},i.n=function(e){var t=e&&e.__esModule?function(){return e["default"]}:function(){return e};return i.d(t,"a",t),t},i.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},i.p="/";var o=window["webpackJsonp"]=window["webpackJsonp"]||[],l=o.push.bind(o);o.push=t,o=o.slice();for(var c=0;c<o.length;c++)t(o[c]);var d=l;s.push([0,"chunk-vendors"]),a()})({0:function(e,t,a){e.exports=a("56d7")},4678:function(e,t,a){var r={"./af":"2bfb","./af.js":"2bfb","./ar":"8e73","./ar-dz":"a356","./ar-dz.js":"a356","./ar-kw":"423e","./ar-kw.js":"423e","./ar-ly":"1cfd","./ar-ly.js":"1cfd","./ar-ma":"0a84","./ar-ma.js":"0a84","./ar-sa":"8230","./ar-sa.js":"8230","./ar-tn":"6d83","./ar-tn.js":"6d83","./ar.js":"8e73","./az":"485c","./az.js":"485c","./be":"1fc1","./be.js":"1fc1","./bg":"84aa","./bg.js":"84aa","./bm":"a7fa","./bm.js":"a7fa","./bn":"9043","./bn.js":"9043","./bo":"d26a","./bo.js":"d26a","./br":"6887","./br.js":"6887","./bs":"2554","./bs.js":"2554","./ca":"d716","./ca.js":"d716","./cs":"3c0d","./cs.js":"3c0d","./cv":"03ec","./cv.js":"03ec","./cy":"9797","./cy.js":"9797","./da":"0f14","./da.js":"0f14","./de":"b469","./de-at":"b3eb","./de-at.js":"b3eb","./de-ch":"bb71","./de-ch.js":"bb71","./de.js":"b469","./dv":"598a","./dv.js":"598a","./el":"8d47","./el.js":"8d47","./en-SG":"cdab","./en-SG.js":"cdab","./en-au":"0e6b","./en-au.js":"0e6b","./en-ca":"3886","./en-ca.js":"3886","./en-gb":"39a6","./en-gb.js":"39a6","./en-ie":"e1d3","./en-ie.js":"e1d3","./en-il":"7333","./en-il.js":"7333","./en-nz":"6f50","./en-nz.js":"6f50","./eo":"65db","./eo.js":"65db","./es":"898b","./es-do":"0a3c","./es-do.js":"0a3c","./es-us":"55c9","./es-us.js":"55c9","./es.js":"898b","./et":"ec18","./et.js":"ec18","./eu":"0ff2","./eu.js":"0ff2","./fa":"8df4","./fa.js":"8df4","./fi":"81e9","./fi.js":"81e9","./fo":"0721","./fo.js":"0721","./fr":"9f26","./fr-ca":"d9f8","./fr-ca.js":"d9f8","./fr-ch":"0e49","./fr-ch.js":"0e49","./fr.js":"9f26","./fy":"7118","./fy.js":"7118","./ga":"5120","./ga.js":"5120","./gd":"f6b4","./gd.js":"f6b4","./gl":"8840","./gl.js":"8840","./gom-latn":"0caa","./gom-latn.js":"0caa","./gu":"e0c5","./gu.js":"e0c5","./he":"c7aa","./he.js":"c7aa","./hi":"dc4d","./hi.js":"dc4d","./hr":"4ba9","./hr.js":"4ba9","./hu":"5b14","./hu.js":"5b14","./hy-am":"d6b6","./hy-am.js":"d6b6","./id":"5038","./id.js":"5038","./is":"0558","./is.js":"0558","./it":"6e98","./it-ch":"6f12","./it-ch.js":"6f12","./it.js":"6e98","./ja":"079e","./ja.js":"079e","./jv":"b540","./jv.js":"b540","./ka":"201b","./ka.js":"201b","./kk":"6d79","./kk.js":"6d79","./km":"e81d","./km.js":"e81d","./kn":"3e92","./kn.js":"3e92","./ko":"22f8","./ko.js":"22f8","./ku":"2421","./ku.js":"2421","./ky":"9609","./ky.js":"9609","./lb":"440c","./lb.js":"440c","./lo":"b29d","./lo.js":"b29d","./lt":"26f9","./lt.js":"26f9","./lv":"b97c","./lv.js":"b97c","./me":"293c","./me.js":"293c","./mi":"688b","./mi.js":"688b","./mk":"6909","./mk.js":"6909","./ml":"02fb","./ml.js":"02fb","./mn":"958b","./mn.js":"958b","./mr":"39bd","./mr.js":"39bd","./ms":"ebe4","./ms-my":"6403","./ms-my.js":"6403","./ms.js":"ebe4","./mt":"1b45","./mt.js":"1b45","./my":"8689","./my.js":"8689","./nb":"6ce3","./nb.js":"6ce3","./ne":"3a39","./ne.js":"3a39","./nl":"facd","./nl-be":"db29","./nl-be.js":"db29","./nl.js":"facd","./nn":"b84c","./nn.js":"b84c","./pa-in":"f3ff","./pa-in.js":"f3ff","./pl":"8d57","./pl.js":"8d57","./pt":"f260","./pt-br":"d2d4","./pt-br.js":"d2d4","./pt.js":"f260","./ro":"972c","./ro.js":"972c","./ru":"957c","./ru.js":"957c","./sd":"6784","./sd.js":"6784","./se":"ffff","./se.js":"ffff","./si":"eda5","./si.js":"eda5","./sk":"7be6","./sk.js":"7be6","./sl":"8155","./sl.js":"8155","./sq":"c8f3","./sq.js":"c8f3","./sr":"cf1e","./sr-cyrl":"13e9","./sr-cyrl.js":"13e9","./sr.js":"cf1e","./ss":"52bd","./ss.js":"52bd","./sv":"5fbd","./sv.js":"5fbd","./sw":"74dc","./sw.js":"74dc","./ta":"3de5","./ta.js":"3de5","./te":"5cbb","./te.js":"5cbb","./tet":"576c","./tet.js":"576c","./tg":"3b1b","./tg.js":"3b1b","./th":"10e8","./th.js":"10e8","./tl-ph":"0f38","./tl-ph.js":"0f38","./tlh":"cf75","./tlh.js":"cf75","./tr":"0e81","./tr.js":"0e81","./tzl":"cf51","./tzl.js":"cf51","./tzm":"c109","./tzm-latn":"b53d","./tzm-latn.js":"b53d","./tzm.js":"c109","./ug-cn":"6117","./ug-cn.js":"6117","./uk":"ada2","./uk.js":"ada2","./ur":"5294","./ur.js":"5294","./uz":"2e8c","./uz-latn":"010e","./uz-latn.js":"010e","./uz.js":"2e8c","./vi":"2921","./vi.js":"2921","./x-pseudo":"fd7e","./x-pseudo.js":"fd7e","./yo":"7f33","./yo.js":"7f33","./zh-cn":"5c3a","./zh-cn.js":"5c3a","./zh-hk":"49ab","./zh-hk.js":"49ab","./zh-tw":"90ea","./zh-tw.js":"90ea"};function n(e){var t=s(e);return a(t)}function s(e){if(!a.o(r,e)){var t=new Error("Cannot find module '"+e+"'");throw t.code="MODULE_NOT_FOUND",t}return r[e]}n.keys=function(){return Object.keys(r)},n.resolve=s,e.exports=n,n.id="4678"},"56d7":function(e,t,a){"use strict";a.r(t);a("cadf"),a("551c"),a("f751"),a("097d");var r=a("2b0e"),n=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{attrs:{id:"app"}},[e.error?a("div",{staticClass:"form-modal-error"},[a("b",[e._v("Folgende Fehler sind aufgetreten:")]),a("ul",[a("li",[e._v(e._s(e.error))])])]):e._e(),a("CalendarForm",{attrs:{kalender:e.kalender,calendarSelected:e.calendarSelected,acl:e.acl}}),a("CalendarEintrag",{attrs:{kalender:e.kalender,acl:e.acl}}),1==e.loading?a("div",{staticClass:"overlay"},[a("i",{staticClass:"fa fas fa-sync-alt fa-spin"})]):e._e(),a("div",{attrs:{id:""}},[a("CalendarList",{attrs:{kalender:e.kalender}}),a("Calendar",{attrs:{eintraege:e.eintraege,kalender:e.kalender,acl:e.acl}})],1)],1)},s=[],i=(a("ac6a"),function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"calendar"},[a("div",{staticClass:"calendar-header"},[a("button",{staticClass:"btn chevron-left margin-r-xs",on:{click:e.prevMonth}},[a("i",{staticClass:"fa fa-arrow-left"}),e._v("Vor\n      ")]),a("button",{staticClass:"btn",on:{click:e.gotoToday}},[a("i",{staticClass:"fa fa-home"}),e._v("Heute\n      ")]),a("h3",[e._v(e._s(e._f("moment")(e.openMonth,"MMMM YYYY")))]),a("button",{staticClass:"btn chevron-right",on:{click:e.nextMonth}},[e._v("\n         Weiter"),a("i",{staticClass:"fa fa-arrow-right"})])]),a("table",{},[a("thead",[a("tr",[a("td",{staticClass:"labelKW"}),e._l(e.days,(function(t,r){return a("td",{key:r,staticClass:"day"},[e._v("\n          "+e._s(t)+"\n        ")])}))],2)]),a("tbody",e._l(e.weekInMonthFormat,(function(t,r){return a("tr",{key:r},[a("td",{staticClass:"labelKW"},[a("span",{staticClass:"text-small"},[e._v("KW")]),a("br"),e._v(e._s(e.kwInMonth(t))+"\n        ")]),e._l(e.daysInWeekFormat(t),(function(t,r){return a("td",{key:r,staticClass:"day",class:{"bg-orange":t[1]==e.getToday},on:{dblclick:function(a){return a.target!==a.currentTarget?null:e.handlerClickAdd(t[1])}}},[a("div",{staticClass:"dayLabel",on:{dblclick:function(a){return a.target!==a.currentTarget?null:e.handlerClickAdd(t[1])}}},[e._v(e._s(e._f("moment")(t[1],"Do")))]),e._l(e.getEintrag(t),(function(r,n){return a("div",{key:n,staticClass:"eintrag",class:{"eintrag-multiple":e.styleMultipe(r)},style:e.styleEintrag(r,t[1]),on:{click:function(t){return e.handlerClickEintrag(r)},mouseover:function(t){return e.handlerMouseoverEintrag(t)},mouseleave:function(t){return e.handlerMouseleaveEintrag(t)}}},[a("div",{staticClass:"date"},[a("strong",["00:00"!=r.startTime?a("span",[e._v("\n                  "+e._s(r.startTime)+"\n                ")]):e._e(),"00:00"!=r.endTime&&0==r.wholeDay?a("span",[e._v("\n                  - "+e._s(r.endTime)+"\n                ")]):e._e()])]),a("div",{staticClass:"title"},[e._v(e._s(r.title))]),r.place||r.comment?a("div",{staticClass:"info margin-t-s flex-row text-gey text-small"},[r.place?a("div",{staticClass:"flex-1"},[a("i",{staticClass:"fas fa-map-marker-alt margin-r-xs"}),e._v(" "+e._s(r.place))]):e._e(),r.comment?a("div",{staticClass:"margin-t-s"},[a("i",{staticClass:"fas fa-comment"}),e._v(" "+e._s(r.comment))]):e._e()]):e._e()])}))],2)}))],2)})),0)])])}),o=[],l=(a("55dd"),{name:"Calendar",props:{eintraege:Array,kalender:Array,acl:Object},data:function(){return{today:this.$moment(),openMonth:!1,openMonthDay:!1,days:["Mo","Di","Mi","Do","Fr","Sa","So"]}},created:function(){this.openMonth=this.$moment(this.today).date(1),this.openMonthDay=this.$moment(this.today).date(1),this.gotoToday()},computed:{weekInMonthFormat:function(){return 5},getToday:function(){return this.today.format("YYYY-MM-DD")}},methods:{handlerMouseoverEintrag:function(e){e.target.classList.add("open")},handlerMouseleaveEintrag:function(e){e.target.classList.remove("open")},handlerClickEintrag:function(e){if(!e)return!1;EventBus.$emit("eintrag--show-open",{eintrag:e})},styleEintrag:function(e,t){var a={},r=this;return this.kalender.forEach((function(n){parseInt(n.kalenderID)==parseInt(e.calenderID)&&(1==r.styleMultipe(e)?(e.startDay==t?a={borderLeft:"5px solid "+n.kalenderColor,marginLeft:"0.6rem"}:e.endDay==t&&(a={borderRight:"5px solid "+n.kalenderColor,marginRight:"0.6rem"}),a.borderBottom="2px solid "+n.kalenderColor):a={borderLeft:"5px solid "+n.kalenderColor})})),a},styleMultipe:function(e){return"0000-00-00"!=e.endDay&&e.endDay!=e.startDay},getEintrag:function(e){if(this.eintraege.length<=0)return"";var t=this,a=[];return this.eintraege.forEach((function(r){var n=new Date(r.eintragDatumStart),s=new Date(r.eintragDatumEnde),i=new Date(e[1]);if(s.getTime()||(s=new Date(r.eintragDatumStart)),n<=i&&i<=s){var o=!1;r.eintragTimeStart==r.eintragTimeEnde&&(o=!0),a.push({id:r.eintragID,title:r.eintragTitel,startDay:r.eintragDatumStart,startTime:t.$moment(r.eintragTimeStart,"HH:mm:ss",!0).format("HH:mm"),endDay:r.eintragDatumEnde,endTime:t.$moment(r.eintragTimeEnde,"HH:mm:ss",!0).format("HH:mm"),wholeDay:o,place:r.eintragOrt,comment:r.eintragKommentar,calenderID:r.kalenderID,categoryID:r.eintragKategorieID,createdTime:r.eintragCreatedTime,modifiedTime:r.eintragModifiedTime,createdUserID:r.eintragUserID,createdUserName:r.eintragUserName})}})),a=a.sort((function(e,t){return moment(e.start).diff(t.start)})),a},handlerClickAdd:function(e){return 1==this.acl.rights.write&&(!!e&&(EventBus.$emit("eintrag--form-open",{form:{startDay:e}}),!1))},kwInMonth:function(e){return this.$moment(this.openMonth).date(7*(e-1)+1).isoWeek()},daysInWeekFormat:function(e){var t=[],a=this.$moment(this.openMonth).date(7*(e-1)+1),r=a.day()-1;a=a.subtract(r,"days");for(var n=0;n<7;n++)t.push([a,this.$moment(a).format("YYYY-MM-DD")]),a=this.$moment(a).add(1,"day");return t},nextMonth:function(){this.openMonth=this.$moment(this.openMonth).add(1,"months")},prevMonth:function(){this.openMonth=this.$moment(this.openMonth).subtract(1,"months")},gotoToday:function(){this.openMonth=this.$moment(this.today).date(1)}}}),c=l,d=a("2877"),m=Object(d["a"])(c,i,o,!1,null,null,null),f=m.exports,u=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"calendar-list"},[a("ul",{staticClass:"noListStyle flex-row"},e._l(e.kalender,(function(t,r){return a("li",{key:r},[a("button",{directives:[{name:"show",rawName:"v-show",value:e.checkAcl(t.kalenderAcl),expression:"checkAcl(item.kalenderAcl)"}],staticClass:"btn margin-r-xs",style:e.styleButton(t.kalenderID,t.kalenderColor),on:{click:function(a){return e.handlerClickKalender(t.kalenderID)}}},[e._v(e._s(t.kalenderName))])])})),0)])},h=[],v={name:"Calendarlist",props:{kalender:Array},data:function(){return{selected:[]}},created:function(){var e=this;EventBus.$on("list--preselected",(function(t){t.selected[0]&&(e.selected=t.selected)}))},computed:{},methods:{checkAcl:function(e){return!e||!e.rights||1==parseInt(e.rights.read)},styleButton:function(e,t){return this.selected.indexOf(parseInt(e))>-1?{backgroundColor:t}:{borderLeft:"5px solid "+t}},handlerClickKalender:function(e){if(e=parseInt(e),e){var t=this.selected.indexOf(e);t>-1?this.selected.splice(t,1):this.selected.push(e)}EventBus.$emit("list--selected",{selected:this.selected})}}},p=v,b=Object(d["a"])(p,u,h,!1,null,null,null),g=b.exports,y=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{directives:[{name:"show",rawName:"v-show",value:e.modalActive,expression:"modalActive"}],staticClass:"form-modal",on:{click:function(t){return t.target!==t.currentTarget?null:e.handlerCloseModal(t)}}},[a("div",{staticClass:"form form-style-2 form-modal-content"},[a("div",{staticClass:"form-modal-close",on:{click:e.handlerCloseModal}},[a("i",{staticClass:"fa fa-times"})]),a("div",{staticClass:"text-small"},[e._v("Datum:")]),a("div",{staticClass:"labelDay"},[e._v(e._s(e.form.startDay))]),a("input",{directives:[{name:"model",rawName:"v-model",value:e.form.id,expression:"form.id"}],attrs:{type:"hidden"},domProps:{value:e.form.id},on:{input:function(t){t.target.composing||e.$set(e.form,"id",t.target.value)}}}),a("input",{directives:[{name:"model",rawName:"v-model",value:e.form.startDay,expression:"form.startDay"}],attrs:{type:"hidden"},domProps:{value:e.form.startDay},on:{input:function(t){t.target.composing||e.$set(e.form,"startDay",t.target.value)}}}),a("br"),a("div",{staticClass:"text-small"},[e._v("Titel:")]),a("input",{directives:[{name:"model",rawName:"v-model",value:e.form.title,expression:"form.title"}],staticClass:"width-100p",attrs:{type:"text",placeholder:"Titel"},domProps:{value:e.form.title},on:{input:function(t){t.target.composing||e.$set(e.form,"title",t.target.value)}}}),a("div",{staticClass:"flex-row margin-t-l"},[a("div",{staticClass:"flex-1"},[a("h4",[e._v("Kalender wählen:")]),a("ul",{staticClass:"noListStyle"},e._l(e.kalender,(function(t,r){return a("li",{key:r,staticClass:"margin-b-s"},[a("button",{directives:[{name:"show",rawName:"v-show",value:e.checkAcl(t.kalenderAcl),expression:"checkAcl(item.kalenderAcl)"}],staticClass:"btn",style:e.styleButton(t.kalenderID,t.kalenderColor),on:{click:function(a){return e.handlerClickKalender(t.kalenderID)}}},[e._v(e._s(t.kalenderName))])])})),0)]),a("div",{staticClass:"flex-1"},[a("ul",{staticClass:"noListStyle"},[a("li",{staticClass:"margin-b-m"},[a("label",{staticClass:"block"},[e._v("Bis:")]),a("date-picker",{attrs:{type:"date",format:"YYYY-MM-DD","default-value":new Date(e.form.startDay)},model:{value:e.form.endDay,callback:function(t){e.$set(e.form,"endDay",t)},expression:"form.endDay"}})],1),a("li",{staticClass:"margin-b-m"},[a("label",{staticClass:"block"},[e._v("Uhrzeit Start:")]),a("vue-timepicker",{attrs:{format:"HH:mm","minute-interval":5},model:{value:e.form.startTime,callback:function(t){e.$set(e.form,"startTime",t)},expression:"form.startTime"}})],1),a("li",{staticClass:"margin-b-m"},[a("label",{staticClass:"block"},[e._v("Uhrzeit Ende:")]),a("vue-timepicker",{attrs:{format:"HH:mm","minute-interval":5},model:{value:e.form.endTime,callback:function(t){e.$set(e.form,"endTime",t)},expression:"form.endTime"}})],1),a("li",[a("label",{staticClass:"text-small block"},[e._v("Ort:")]),a("input",{directives:[{name:"model",rawName:"v-model",value:e.form.place,expression:"form.place"}],attrs:{type:"text"},domProps:{value:e.form.place},on:{input:function(t){t.target.composing||e.$set(e.form,"place",t.target.value)}}})]),a("li",[a("label",{staticClass:"text-small block"},[e._v("Notiz:")]),a("textarea",{directives:[{name:"model",rawName:"v-model",value:e.form.comment,expression:"form.comment"}],domProps:{value:e.form.comment},on:{input:function(t){t.target.composing||e.$set(e.form,"comment",t.target.value)}}})])])])]),a("hr"),a("button",{staticClass:"btn width-100p",on:{click:e.handlerClickAddEintrag}},[a("i",{staticClass:"fa fa-save"}),e._v("Speichern")])])])},j=[],k=(a("a481"),a("ec45")),C=(a("411c"),a("a6d7"),a("97fc")),_=a.n(C),D={name:"CalendarForm",components:{VueTimepicker:_.a,DatePicker:k["default"]},props:{kalender:Array,calendarSelected:Array,acl:Object},data:function(){return{modalActive:!1,form:{calenderID:0,startDay:"",startTime:"00:00:00",endDay:"",endTime:"00:00:00",title:"",place:"",comment:""}}},watch:{calendarSelected:function(){this.calendarSelected[0]&&(this.form.calenderID=this.calendarSelected[0])}},created:function(){var e=this,t=this;EventBus.$on("eintrag--form-open",(function(a){if(!a.form.startDay)return!1;a.form.id&&(e.form.id=a.form.id),a.form.calenderID&&(e.form.calenderID=a.form.calenderID),a.form.startDay&&(e.form.startDay=a.form.startDay),a.form.startTime&&(e.form.startTime=a.form.startTime),a.form.endDay&&(e.form.endDay=new Date(a.form.endDay)),a.form.endTime&&(e.form.endTime=a.form.endTime),a.form.title&&(e.form.title=a.form.title),a.form.place&&(e.form.place=a.form.place),a.form.comment&&(e.form.comment=t.strippedContent(a.form.comment)),e.modalActive=!0})),EventBus.$on("eintrag--form-reset",(function(t){e.form={id:0,calenderID:e.form.calenderID,startDay:"",startTime:"00:00:00",endDay:"",endTime:"00:00:00",title:"",place:"",comment:""}}))},computed:{formInputEndDay:function(){return new Date(this.form.startDay)}},methods:{strippedContent:function(e){var t=/(<([^>]+)>)/gi;return e.replace(t,"")},checkAcl:function(e){return!e||!e.rights||1==parseInt(e.rights.write)},styleButton:function(e,t){return this.form.calenderID==e?{backgroundColor:t,borderLeft:"5px solid "+t}:{borderLeft:"5px solid "+t}},handlerClickKalender:function(e){return e&&(this.form.calenderID=e),!1},handlerClickAddEintrag:function(){if(""!=this.form.startDay&&""!=this.form.title&&""!=this.form.calenderID){var e={id:this.form.id,calenderID:this.form.calenderID,startTime:this.form.startTime,endTime:this.form.endTime,title:this.form.title,place:this.form.place,comment:this.form.comment,startDay:this.form.startDay,endDay:this.$moment(this.form.endDay).format("YYYY-MM-DD")};EventBus.$emit("eintrag--submit",{form:e}),this.modalActive=!1}return this.formErrors=[],""==this.form.startDay&&this.formErrors.push("Day required."),""==this.form.title&&this.formErrors.push("Title required."),""==this.form.calenderID&&this.formErrors.push("Kalender required."),!1},handlerCloseModal:function(){this.modalActive=!1}}},w=D,x=Object(d["a"])(w,y,j,!1,null,null,null),E=x.exports,T=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{directives:[{name:"show",rawName:"v-show",value:e.modalActive,expression:"modalActive"}],staticClass:"form-modal",on:{click:function(t){return t.target!==t.currentTarget?null:e.handlerCloseModal(t)}}},[a("div",{staticClass:"form form-style-2 form-modal-content"},[a("div",{staticClass:"form-modal-close",on:{click:e.handlerCloseModal}},[a("i",{staticClass:"fa fa-times"})]),a("div",{staticClass:"text-small text-gey"},[e._v("Datum und Uhrzeit:")]),a("div",{staticClass:"labelDay"},[e._v("\n      "+e._s(e.form.startDay)+"\n      "),"0000-00-00"!=e.form.endDay?a("span",[e._v("bis "+e._s(e.form.endDay))]):e._e()]),0==e.form.wholeDay?a("div",{staticClass:"labelTime"},["00:00"!=e.form.startTime?a("span",[e._v(e._s(e.form.startTime))]):e._e(),"00:00"!=e.form.endTime?a("span",[e._v(" - "+e._s(e.form.endTime))]):e._e()]):e._e(),1==e.form.wholeDay?a("div",["00:00"!=e.form.startTime?a("div",{staticClass:"labelTime"},[e._v(e._s(e.form.startTime))]):e._e(),e._v("\n      Ganztägig\n    ")]):e._e(),a("br"),a("div",{staticClass:"text-small text-gey"},[e._v("Titel:")]),a("div",{staticClass:"labelDay"},[e._v(e._s(e.form.title))]),a("br"),a("div",{staticClass:"flex-row"},[a("div",{staticClass:"flex-1"},[a("ul",{staticClass:"noListStyle"},[e.form.place?a("li",[e._m(0),e._v("\n            "+e._s(e.form.place)+"\n          ")]):e._e(),e.form.comment?a("li",{staticClass:"margin-t-m"},[e._m(1),a("br"),a("span",{domProps:{innerHTML:e._s(e.form.comment)}},[e._v(e._s(e.form.comment))])]):e._e(),a("li",{staticClass:"margin-t-l"},[a("div",{staticClass:"btn noCursor",style:{backgroundColor:e.formKalender.kalenderColor}},[e._v(e._s(e.formKalender.kalenderName))])])])])]),a("br"),a("button",{directives:[{name:"show",rawName:"v-show",value:e.acl.rights.write,expression:"acl.rights.write"}],staticClass:"btn margin-r-s",on:{click:e.handlerClickEdit}},[a("i",{staticClass:"fa fa-edit"}),e._v("Bearbeiten")]),a("button",{directives:[{name:"show",rawName:"v-show",value:!e.deleteBtn&&e.acl.rights.delete,expression:"!deleteBtn && acl.rights.delete"}],staticClass:"btn",on:{click:e.handlerClickDelete}},[a("i",{staticClass:"fa fa-trash"}),e._v("Löschen")]),a("button",{directives:[{name:"show",rawName:"v-show",value:e.deleteBtn,expression:"deleteBtn"}],staticClass:"btn btn-red",on:{click:e.handlerClickDeleteSecond}},[e._v("Endgültig Entfernen!")]),a("div",{directives:[{name:"show",rawName:"v-show",value:e.acl.rights.write,expression:"acl.rights.write"}]},[a("hr"),a("div",{staticClass:"text-small text-gey"},[a("b",[e._v("Erstellt von:")]),a("div",[e._v(e._s(e.form.createdUserName))]),a("div",[e._v(e._s(e.form.createdTime)+" - "+e._s(e.form.modifiedTime))])])])])])},M=[function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("label",{staticClass:"text-small text-gey"},[a("i",{staticClass:"fas fa-map-marker-alt margin-r-xs"}),e._v("Ort:")])},function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("label",{staticClass:"text-small text-gey"},[a("i",{staticClass:"fas fa-comment margin-r-xs"}),e._v("Notiz:")])}],$={name:"CalendarEintrag",components:{},props:{kalender:Array,acl:Object},data:function(){return{modalActive:!1,form:{},formKalender:{},deleteBtn:!1}},created:function(){var e=this,t=this;EventBus.$on("eintrag--show-open",(function(a){a.eintrag.title&&(t.form=a.eintrag,t.formKalender=e.getKalender(a.eintrag)),t.modalActive=!0}))},computed:{},methods:{handlerClickDelete:function(){this.deleteBtn=!0},handlerClickDeleteSecond:function(){return!!this.form.id&&(EventBus.$emit("eintrag--delete",{id:this.form.id}),this.handlerCloseModal(),!1)},handlerClickEdit:function(){return!!this.form.startDay&&(EventBus.$emit("eintrag--form-open",{form:this.form}),this.handlerCloseModal(),!1)},getKalender:function(e){if(!e||!e.calenderID)return!1;var t=parseInt(e.calenderID);if(!t)return!1;var a=!1;return this.kalender.forEach((function(e,r){parseInt(e.kalenderID)==t&&(a=e)})),a},handlerCloseModal:function(){this.modalActive=!1,this.deleteBtn=!1}}},I=$,S=Object(d["a"])(I,T,M,!1,null,null,null),B=S.exports,A=a("bc3a").default,z={name:"app",components:{Calendar:f,CalendarList:g,CalendarForm:E,CalendarEintrag:B},data:function(){return{loading:!0,error:!1,calendarSelected:[],kalender:[],eintraege:[],acl:!1}},created:function(){this.acl=globals.acl;var e=this;e.ajaxGet("rest.php/GetKalender",{},(function(e,t){1==e.data.error&&e.data.msg?t.error=e.data.msg:e.data.list&&t.acl.rights.read&&(t.kalender=e.data.list,t.kalender.forEach((function(e,a){1==e.kalenderPreSelect&&t.calendarSelected.push(parseInt(e.kalenderID))})),EventBus.$emit("list--preselected",{selected:t.calendarSelected}),EventBus.$emit("eintrag--load",{}))})),EventBus.$on("list--selected",(function(t){e.calendarSelected=t.selected,EventBus.$emit("eintrag--load",{})})),EventBus.$on("eintrag--load",(function(t){e.ajaxGet("rest.php/GetKalenderEintrag/"+e.calendarSelected.join("-"),{},(function(e,t){1==e.data.error&&e.data.msg?t.error=e.data.msg:(e.data&&e.data.list&&t.acl.rights.read?t.eintraege=e.data.list:t.eintraege=[],t.error="")}))})),EventBus.$on("eintrag--delete",(function(t){return 1!=e.acl.rights.delete?(e.error="Keine Löschrechte!",!1):!!t.id&&void e.ajaxPost("rest.php/DeleteKalenderEintrag",{data:t.id},{},(function(e,t){1==e.data.error&&e.data.msg?t.error=e.data.msg:1==e.data.done&&EventBus.$emit("eintrag--load",{})}))})),EventBus.$on("eintrag--submit",(function(t){return 1!=e.acl.rights.write?(e.error="Keine Schreibrechte!",!1):(""!=t.form.start||""!=t.form.title||""!=t.form.calenderID)&&void e.ajaxPost("rest.php/SetKalenderEintrag",{data:t.form},{},(function(t){1==t.data.error&&t.data.msg?e.error=t.data.msg:1==t.data.done&&(EventBus.$emit("eintrag--form-reset",{}),EventBus.$emit("eintrag--load",{}))}))}))},methods:{ajaxGet:function(e,t,a,r,n){this.loading=!0;var s=this;A.get(e,{params:t}).then((function(e){a&&"function"===typeof a&&a(e,s)})).catch((function(e){e&&"function"===typeof r&&r(e)})).finally((function(){n&&"function"===typeof n&&n(),s.loading=!1}))},ajaxPost:function(e,t,a,r,n,s){this.loading=!0;var i=this;A.post(e,t,{params:a}).then((function(e){r&&"function"===typeof r&&r(e,i)})).catch((function(e){e&&"function"===typeof n&&n(e)})).finally((function(){s&&"function"===typeof s&&s(),i.loading=!1}))}}},O=z,K=Object(d["a"])(O,n,s,!1,null,null,null),N=K.exports;window.EventBus=new r["a"];var Y=a("c1df");a("b469"),r["a"].use(a("2ead"),{moment:Y}),r["a"].config.productionTip=!1;var L=!1;L=L||{objekt:!1},new r["a"]({render:function(e){return e(N)}}).$mount("#app")}});
//# sourceMappingURL=app.js.map