function createImage(e){var a=document.createElement("img");return a.src=e,a}function measureText(e,a){var t=document.createElement("span");t.style.font=a,t.style.visibility="hidden",t.style.position="fixed",t.style.zIndex=-1,t.appendChild(document.createTextNode(e)),document.body.appendChild(t);var o=t.getBoundingClientRect().width;return document.body.removeChild(t),o}function createMarkerIcon(e,a,t){var o=document.createElement("canvas"),r=o.getContext("2d");return o.width=e.width,o.height=e.height,r.drawImage(e,0,0,e.width,e.height),r.font=t.font,r.fillStyle=t.color,r.fillText(a,t.left,t.top),o.toDataURL()}function decodeGeometry(e){e=JSON.parse(e);for(var a=e.length,t=[],o=0;a>o;o++)t.push(google.maps.geometry.encoding.decodePath(e[o]));return t}function Area(e,a){this.attrs=e,this.type=a}function Product(e){this.attrs=e}function initInfoBox(){InfoBox=function(e){google.maps.OverlayView.call(this),"undefined"==typeof e&&(e={}),this.opts=e,this.viewHolder=document.createElement("div"),this.viewHolder.style.position="absolute",this.opts.content&&this.setContent(this.opts.content)},InfoBox.prototype=new google.maps.OverlayView,InfoBox.prototype.draw=function(){var e=this.getProjection().fromLatLngToDivPixel(this.position);this.viewHolder.style.left=e.x-this.viewHolder.offsetWidth/2+"px";var a=e.y-this.viewHolder.offsetHeight;this.anchor instanceof google.maps.Marker&&(a-=this.anchor.getShape().coords[3]),this.viewHolder.style.top=a+"px",this.opts.disableAutoPan||this.boundsChangedListener&&this.panMap(),this.opts.onDraw&&this.opts.onDraw.apply(this)},InfoBox.prototype.remove=function(){this.viewHolder.parentNode.removeChild(this.viewHolder)},InfoBox.prototype.onAdd=function(){var e=this.getPanes();e.floatPane.appendChild(this.viewHolder);var a=this;this.opts.disableAutoPan||(this.boundsChangedListener=google.maps.event.addListener(this.map,"bounds_changed",function(){return a.panMap.apply(a)}))},InfoBox.prototype.open=function(e,a){var t,o;e instanceof google.maps.Marker?(t=e.getPosition(),o=e.getMap()):e instanceof google.maps.Map&&(t=a?a:e.getCenter(),o=e),(this.anchor!==e||this.position.lat()!=t.lat()&&this.position.lng()!=t.lng())&&(this.anchor=e,this.position=t,this.setMap(o))},InfoBox.prototype.close=function(){this.anchor=null,this.position=null,this.setMap(null)},InfoBox.prototype.setContent=function(e){if(this.content=e,"string"==typeof e)this.viewHolder.innerHTML=e;else{for(;this.viewHolder.firstChild;)this.viewHolder.removeChild(this.viewHolder.firstChild);this.viewHolder.appendChild(e)}},InfoBox.prototype.panMap=function(){var e=this.getMap(),a=e.getBounds();if(a){var t=a.getSouthWest().lng(),o=a.getNorthEast().lng(),r=a.getNorthEast().lat(),n=a.getSouthWest().lat(),i=this.getBounds(20,20),l=i.getSouthWest().lng(),s=i.getNorthEast().lng(),m=i.getNorthEast().lat(),p=i.getSouthWest().lat(),d=(t>l?t-l:0)+(s>o?o-s:0),f=(m>r?r-m:0)+(n>p?n-p:0),c=e.getCenter(),g=c.lng()-d,u=c.lat()-f;e.panTo(new google.maps.LatLng(u,g)),google.maps.event.removeListener(this.boundsChangedListener),this.boundsChangedListener=null}},InfoBox.prototype.getBounds=function(e,a){var t=this.getMap();if(t){var o=t.getBounds();if(o){var r=t.getDiv(),n=r.offsetWidth,i=r.offsetHeight,l=o.toSpan(),s=l.lng(),m=l.lat(),p=s/n,d=m/i,f=this.anchor instanceof google.maps.Marker?this.anchor.__gm.Gf.shape.coords[3]:0,c=this.position.lng()+(-(this.viewHolder.offsetWidth/2)-e)*p,g=this.position.lng()+(this.viewHolder.offsetWidth/2+e)*p,u=this.position.lat()-(-this.viewHolder.offsetHeight-f-a)*d,M=this.position.lat()-(a-f)*d;return new google.maps.LatLngBounds(new google.maps.LatLng(M,c),new google.maps.LatLng(u,g))}}}}var markerIcon={PADDING:8,MIN_WIDTH:24,IMG:null,IMG_HOVER:null,COLOR:"black",COLOR_HOVER:"white",FONT:"12px Arial",ARROW_HEIGHT:0,TEXT_HEIGHT:0,MAX_WIDTH:0,MAX_HEIGHT:0,create:function(e,a){var t=a?markerIcon.IMG_HOVER:markerIcon.IMG,o=a?markerIcon.COLOR_HOVER:markerIcon.COLOR,r={color:o,font:markerIcon.FONT},n=Math.round(measureText(e,r.font)),i=n+2*markerIcon.PADDING;i<=markerIcon.MIN_WIDTH?(i=markerIcon.MIN_WIDTH,r.left=(i-n)/2):r.left=markerIcon.PADDING;var l=Math.round(i*t.height/t.width),s=l*markerIcon.ARROW_HEIGHT;return r.top=(l-s)/2+markerIcon.TEXT_HEIGHT,t.width=i,t.height=l,markerIcon.MAX_WIDTH<i&&(markerIcon.MAX_WIDTH=i,markerIcon.MAX_HEIGHT=l),{icon:createMarkerIcon(t,e,r),width:i,height:l}}};markerIcon.IMG=createImage("data:image/png;base64,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"),markerIcon.IMG_HOVER=createImage("data:image/png;base64,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"),markerIcon.ARROW_HEIGHT=4/24,markerIcon.TEXT_HEIGHT=4.5,Area.prototype.getPaths=function(){var e,a=this.attrs;return a.geometry&&(e=decodeGeometry(a.geometry)),e},Area.prototype.getBounds=function(){var e,a=this.getPaths();if(a){e=new google.maps.LatLngBounds;for(var t=a.length,o=0;t>o;o++)for(var r=a[o],n=r.length,i=0;n>i;i++)e.extend(r[i])}return e},Area.prototype.getCenter=function(){var e,a=this.attrs;if(a.center)centerP=JSON.parse(a.center),e=new google.maps.LatLng(centerP[0],centerP[1]);else{var t=this.getBounds();t&&(e=t.getCenter())}return e},Area.prototype.getName=function(){var e=this.attrs;return e.pre?e.pre+" "+e.name:e.name},Area.prototype.draw=function(e){var a=this.getPaths(),t=this;if("street"==this.type)a&&(this.poly=new google.maps.Polyline({map:e,path:a[0],strokeColor:m2Map.polygonColor,strokeOpacity:.8,strokeWeight:6,geodesic:!0}));else{a&&(this.poly=new google.maps.Polygon({map:e,paths:a,strokeColor:m2Map.polygonColor,strokeOpacity:.8,strokeWeight:1,fillColor:m2Map.polygonColor,fillOpacity:.2}),this.poly.addListener("mouseover",function(){t.marker&&t.marker.set("clickable",!1),t.mouseover()}),this.poly.addListener("mouseout",function(){t.marker&&t.marker.set("clickable",!0),t.mouseout()}),this.poly.addListener("click",function(){t.nextZoomLevel()}));var o=this.getCenter();o&&this.attrs.total>0&&(this.marker=new google.maps.Marker({map:e,position:o}),m2Map.setIcon(this.marker,this.attrs.total,0),this.marker.addListener("mouseover",function(){t.mouseover()}),this.marker.addListener("mouseout",function(){t.mouseout()}),this.marker.addListener("click",function(){t.nextZoomLevel()}))}},Area.prototype.remove=function(){this.poly&&this.poly.setMap(null),this.marker&&this.marker.setMap(null)},Area.prototype.mouseover=function(){m2Map.infoBoxHover.setContent('<div class="info-wrap-single" style="margin-bottom: 12px;"><div style="padding: 6px 12px; font-weight: bold; font-size: 13px; white-space: nowrap">'+this.getName()+'</div><div class="arrow"></div></div>'),this.marker?m2Map.infoBoxHover.open(this.marker):m2Map.infoBoxHover.open(m2Map.map,this.getCenter())},Area.prototype.mouseout=function(){m2Map.infoBoxHover.close()},Area.prototype.nextZoomLevel=function(){for(var e=m2Map.map.getZoom(),a=e+1,t=form.af.filter(s.iz).val(),o=form.getFocusLocation(),r=m2Map.getZoomAreaLevel(e,t,o.type),n=o.type;m2Map.getZoomAreaLevel(a,t,n)==r&&a<m2Map.detailZoomLevel;)a++;m2Map.map.setCenter(this.getCenter()),m2Map.map.setZoom(a)},Product.prototype.getMarkerKey=function(){return this.attrs.lat+"-"+this.attrs.lng},Product.prototype.getPosition=function(){return new google.maps.LatLng(this.attrs.lat,this.attrs.lng)},s.rect="#rect",s.ra="#ra",s.rm="#rm",s.rl="#rl",s.raK="#ra_k",s.iz="#iz",s.z="#z",s.c="#c",s.page="#page",s.did="#did";var contentHolder=$("#content-holder"),detailListingWrap=$(".detail-listing-dt"),m2Map={loadingTimeout:null,loadingList:$("#loading-list"),progressBar:$("#progress-bar"),mapEl:$("#map"),polygonColor:"#00a769",map:null,markers:{},areas:{},areasLevel:{city:3,district:2,ward:1},deteilZoomLevelDefault:16,detailZoomLevel:16,infoBoxHover:null,boundsChangedEvent:null,zoomChangedEvent:null,closeDetailListener:null,currentDrawState:null,markerIconCached:{},shape:{coords:[0,0,24,28],type:"rect"},wrapListing:$(".wrap-listing"),initMap:function(){History.Adapter.bind(window,"statechange",m2Map.stateChange),initInfoBox(),m2Map.infoBoxHover=new InfoBox({disableAutoPan:!0});var e=form.afZoom.val(),a=form.afCenter.val();m2Map.mapOptions={center:{lat:10.783091,lng:106.704899},zoom:18,mapTypeControl:!0,mapTypeControlOptions:{style:google.maps.MapTypeControlStyle.DROPDOWN_MENU}},e&&a?(m2Map.initMapRe(e,a),m2Map.hasMapInstance()):m2Map.initMapFresh(m2Map.hasMapInstance)},hasMapInstance:function(){var e=form.af.filter(s.did).val();e&&m2Map.detail(e),m2Map.addDrawControl()},stateChange:function(){},pushState:function(e){e||(e=form.serialize()),e=decodeURIComponent(e),History.pushState({},document.title,actionId+"?"+e)},initMapRe:function(e,a){m2Map.mapOptions.center=m2Map.urlValueToLatLng(a),m2Map.mapOptions.zoom=Number(e);m2Map.map=new google.maps.Map(m2Map.mapEl.get(0),m2Map.mapOptions);m2Map.initMapReFirstLoad(),google.maps.event.addListenerOnce(m2Map.map,"idle",m2Map.InitMapReIdle),m2Map.detectHasChange()},initMapReFirstLoad:function(){var e=form.getFocusLocation();if("project_building"==e.type)m2Map.changeLocation(m2Map.drawBuildingProject);else if("street"==e.type)m2Map.currentDrawState=e.type,form.af.filter(s.rl).val(""),form.af.filter(s.ra).val(e.type),form.af.filter(s.raK).val("id"),m2Map.ajaxRequest=m2Map.get(function(e){m2Map.drawStreet(e),e.ra&&e.ra.length&&m2Map.drawArea(new Area(e.ra[0],"street"))});else{var a=Number(form.af.filter(s.iz).val()),t=m2Map.mapOptions.zoom,o={ward:0,district:1,city:2};if(a+o[e.type]>=m2Map.deteilZoomLevelDefault?"city"==e.type?m2Map.detailZoomLevel=a+3:"district"==e.type?m2Map.detailZoomLevel=a+2:"ward"==e.type&&(m2Map.detailZoomLevel=a+1):m2Map.detailZoomLevel=m2Map.deteilZoomLevelDefault,t<m2Map.detailZoomLevel){var r=m2Map.getZoomAreaLevel(t,a,e.type);m2Map.currentDrawState=r,form.af.filter(s.ra).val(r);var n=e.type==r?"id":e.type+"_id";form.af.filter(s.raK).val(n);var i=form.fields.filter(s.rect).prop("disabled",!0);m2Map.ajaxRequest=m2Map.get(function(e){e.ra&&m2Map.drawAreas(e.ra,r)}),i.prop("disabled",!1)}else m2Map.currentDrawState="detail",m2Map.loadDetail("")}},InitMapReIdle:function(){form.afRect.val(m2Map.getBounds(0,0,0,0).toUrlValue()),m2Map.boundsChangedEvent=m2Map.map.addListener("bounds_changed",m2Map.boundsChanged),m2Map.zoomChangedEvent=m2Map.map.addListener("zoom_changed",m2Map.zoomChanged)},initMapFresh:function(e){m2Map.changeLocation(function(a){m2Map.drawMap(a),e()})},drawMap:function(e){m2Map.map=new google.maps.Map(m2Map.mapEl.get(0),m2Map.mapOptions);m2Map.drawLocation(e),m2Map.detectHasChange(),google.maps.event.addListenerOnce(m2Map.map,"bounds_changed",m2Map.setInitLocationProps)},detectHasChange:function(){var e,a,t=m2Map.map;google.maps.event.addListenerOnce(m2Map.map,"bounds_changed",function(){var o=t.getZoom();e=o,a=t.getCenter().toString()}),google.maps.event.addListenerOnce(m2Map.map,"idle",function(){var o=t.getZoom();if(o!=e||t.getCenter().toString()!=a){var r=form.getFocusLocation();"project_building"==r.type?"project_building"==m2Map.currentDrawState:"street"==r.type?"street"==m2Map.currentDrawState:o<m2Map.detailZoomLevel?(m2Map.zoomChanged(),m2Map.removeAllDetail()):(m2Map.currentDrawState="detail",m2Map.removeAreas(),m2Map.ajaxRequest&&(m2Map.ajaxRequest.abort(),m2Map.ajaxRequest=null),m2Map.infoBoxHover.close(),form.af.filter(s.page).val(""),form.af.filter(s.ra).val(""),form.af.filter(s.rm).val(1),form.af.filter(s.rl).val(1),m2Map.ajaxRequest=m2Map.get(function(e){m2Map.removeAreas(),m2Map.drawDetailCallBack(e)})),form.afRect.val(m2Map.getBounds(0,0,0,0).toUrlValue()),form.afZoom.val(m2Map.map.getZoom()),form.afCenter.val(m2Map.getCenter().toUrlValue()),m2Map.pushState()}})},setInitLocationProps:function(){var e=m2Map.map.getZoom(),a=form.getFocusLocation();m2Map.currentDrawState=a.type,form.af.filter(s.iz).val(e);var t={ward:0,district:1,city:2};e+t[a.type]>=m2Map.deteilZoomLevelDefault?"city"==a.type?m2Map.detailZoomLevel=e+3:"district"==a.type?m2Map.detailZoomLevel=e+2:"ward"==a.type&&(m2Map.detailZoomLevel=e+1):m2Map.detailZoomLevel=m2Map.deteilZoomLevelDefault},boundsChanged:function(){var e=m2Map.map;clearTimeout(e.get("bounds_changed_timeout")),e.set("bounds_changed_timeout",setTimeout(function(){form.afRect.val(m2Map.getBounds(0,0,0,0).toUrlValue()),form.afZoom.val(m2Map.map.getZoom()),form.afCenter.val(m2Map.getCenter().toUrlValue()),"detail"==m2Map.currentDrawState?m2Map.loadDetail(1):"street"==m2Map.currentDrawState&&m2Map.loadDetail(1),m2Map.pushState()},100))},zoomChanged:function(){var e=m2Map.map.getZoom();if("project_building"==m2Map.currentDrawState||"street"==m2Map.currentDrawState)return!1;if(e<m2Map.detailZoomLevel){form.af.filter(s.rl).val(""),"detail"==m2Map.currentDrawState&&(m2Map.removeAllDetail(),form.af.filter(s.rm).val(""),form.af.filter(s.page).val(""),form.af.filter(s.rl).val(1));var a=form.getFocusLocation(),t=m2Map.getZoomAreaLevel(m2Map.map.getZoom(),form.af.filter(s.iz).val(),a.type);if(m2Map.currentDrawState!=t){m2Map.ajaxRequest&&(m2Map.ajaxRequest.abort(),m2Map.ajaxRequest=null),m2Map.infoBoxHover.close(),m2Map.currentDrawState=t,form.af.filter(s.ra).val(t);var o=a.type==t?"id":a.type+"_id";form.af.filter(s.raK).val(o);var r=form.fields.filter(s.rect).prop("disabled",!0);m2Map.ajaxRequest=m2Map.get(function(e){e.ra&&m2Map.drawAreas(e.ra,t),e.rl&&m2Map.drawList(e.rl)}),r.prop("disabled",!1)}}else m2Map.currentDrawState="detail",m2Map.removeAreas()},loadDetail:function(e){m2Map.ajaxRequest&&(m2Map.ajaxRequest.abort(),m2Map.ajaxRequest=null),m2Map.infoBoxHover.close(),form.af.filter(s.page).val(""),form.af.filter(s.ra).val(""),form.af.filter(s.rm).val(1),form.af.filter(s.rl).val(e),m2Map.ajaxRequest=m2Map.get(m2Map.drawDetailCallBack)},drawLocation:function(e){var a=form.getFocusLocation();if(e.rm&&("street"==a.type?m2Map.drawStreet(e):m2Map.drawBuildingProject(e,!0)),e.ra&&e.ra.length){var t=new Area(e.ra[0],a.type);if(m2Map.drawAndFitArea(t),"street"==a.type){var o=t.getBounds(),r=t.getCenter();o||r||e.rm&&e.rm.length&&m2Map.setCenter({lat:Number(e.rm[0].lat),lng:Number(e.rm[0].lng)})}}google.maps.event.addListenerOnce(m2Map.map,"idle",m2Map.drawLocationCallback)},drawBuildingProject:function(e,a){m2Map.removeAllDetail(),m2Map.removeAreas();var t=$(".infor-duan-suggest");if(t.length&&t.data("lat")&&t.data("lng"))var o={lat:t.data("lat"),lng:t.data("lng")};else if(e.rm.length)var o={lat:Number(e.rm[0].lat),lng:Number(e.rm[0].lng)};if(o){a===!0&&(m2Map.setCenter(o),m2Map.map.setZoom(m2Map.deteilZoomLevelDefault));var r=new google.maps.Marker({map:m2Map.map,position:o}),n=[];for(var i in e.rm)n.push(new Product(e.rm[i]));r.addListener("click",m2Map.markerClick),r.set("products",n),m2Map.setIcon(r,n.length,0),m2Map.markers[o]=r}},drawLocationCallback:function(){null==m2Map.boundsChangedEvent&&(m2Map.boundsChangedEvent=m2Map.map.addListener("bounds_changed",m2Map.boundsChanged)),null==m2Map.zoomChangedEvent&&(m2Map.zoomChangedEvent=m2Map.map.addListener("zoom_changed",m2Map.zoomChanged))},drawAndFitArea:function(e){var a=e.getBounds();if(a)m2Map.fitBounds(e.getBounds());else{var t=e.getCenter();t&&m2Map.setCenter(t)}m2Map.drawArea(e)},changeLocation:function(e){var a=form.getFocusLocation();"project_building"==a.type?form.af.filter(s.rm).val(1):(form.af.filter(s.ra).val(a.type),form.af.filter(s.raK).val("id"),"street"==a.type&&form.af.filter(s.rm).val(1));var t=form.fields.filter(s.rect);"project_building"==a.type&&t.prop("disabled",!0),m2Map.get(e),"project_building"==a.type&&t.prop("disabled",!1)},drawStreet:function(e){e.rm&&(m2Map.removeAllDetail(),m2Map.drawDetail(e.rm))},removeAreas:function(){var e=m2Map.areas;e.length;for(var a in e)e[a].remove();m2Map.areas={}},drawAreas:function(e,a){m2Map.removeAreas();for(var t=e.length,o=0;t>o;o++){var r=new Area(e[o],a);m2Map.drawArea(r)}},drawArea:function(e){e.draw(m2Map.map),m2Map.areas[e.attrs.id]=e},drawDetailCallBack:function(e){e.rm&&(m2Map.removeAllDetail(),m2Map.drawDetail(e.rm)),e.rl&&m2Map.drawList(e.rl)},drawDetail:function(e){for(var a=m2Map.map,t=m2Map.markers,o=e.length,r=0;o>r;r++){var n=new Product(e[r]),i=n.getMarkerKey(),l=t[i];if(l){var s=l.get("products");s.push(n),l.set("products",s),m2Map.setIcon(l,s.length,0)}else l=new google.maps.Marker({map:a,position:n.getPosition()}),l.addListener("click",m2Map.markerClick),l.set("products",[n]),m2Map.setIcon(l,1,0),t[i]=l}},markerClick:function(){var e=this.get("products");if(1==e.length){var a=e[0];m2Map.showDetail(a.attrs.id)}},removeAllDetail:function(){var e=m2Map.markers;for(var a in e)e[a].setMap(null);m2Map.markers={}},drawList:function(e){contentHolder.html(e)},getBounds:function(e,a,t,o,r){return m2Map.map.getBounds()},getCenter:function(){return m2Map.map.getCenter()},setCenter:function(e){m2Map.map.setCenter(e)},fitBounds:function(e){m2Map.map.fitBounds(e)},setIcon:function(e,a,t){if(1==a){var o="/images/marker-"+t+".png";e.setShape(m2Map.shape)}else{var r=a+"-"+t,n=m2Map.markerIconCached[r];if(n)var i=n;else{var i=markerIcon.create(a,t);m2Map.markerIconCached[r]=i}var o=i.icon,l={coords:[0,0,i.width,i.height],type:"rect"};e.setShape(l)}e.setIcon(o)},resetAf:function(){},get:function(e,a){a||(a=form.serialize());var t=form.af.filter(s.rl).val(),o=form.af.filter(s.ra).val()||form.af.filter(s.rm).val();return o&&m2Map.loading(10),t&&m2Map.loadingList.show(),$.ajax({url:form.el.attr("action"),data:a,success:e,complete:function(){t&&(m2Map.loadingList.hide(),m2Map.wrapListing.scrollTop(0)),o&&m2Map.loaded()}})},urlValueToLatLng:function(e){var a=e.split(",");return new google.maps.LatLng(a[0],a[1])},getZoomAreaLevel:function(e,a,t){a=Number(a);var o=m2Map.areasLevel[t],r=m2Map.detailZoomLevel-a,n=m2Map.detailZoomLevel-e;if(n>r)return t;var i=Math.ceil(r/o),l=Math.ceil((r-i)/(o-1))+i;return i>=n?"ward":l>=n?"district":"city"},getFocusMarker:function(e){var a=m2Map.markers;for(var t in a)for(var o=a[t],r=o.get("products"),n=r.length,i=0;n>i;i++)if(r[i].attrs.id==e)return o},focusMarker:function(e){m2Map.setIcon(e,e.get("products").length,1),e.setZIndex(google.maps.Marker.MAX_ZINDEX++)},showDetail:function(e){m2Map.detail(e),form.af.filter(s.did).val(e),m2Map.pushState()},detail:function(e){var a=$(".wrap-listing-item .inner-wrap").outerWidth(),t=$(".detail-listing");detailListingWrap.loading({full:!1}),detailListingWrap.css({right:a+"px"}),google.maps.event.removeListener(m2Map.closeDetailListener),m2Map.closeDetailListener=m2Map.map.addListener("click",m2Map.closeDetail),$.get("/listing/detail",{id:e},function(e){var a=$(e).find("#detail-wrap");a.find(".popup-common").each(function(){var e=$(this),a=e.attr("id");$("body").find("#"+a).remove()}),t.find(".container").html($(e).find("#detail-wrap").html()),t.find(".popup-common").appendTo("body");new Swiper(".swiper-container",{pagination:".swiper-pagination",paginationClickable:!0,spaceBetween:0});detailListingWrap.loading({done:!0}),$(".inner-detail-listing").scrollTop(0),$(".btn-extra").attr("href",t.find(".btn-copy").data("clipboard-text"))})},closeDetail:function(e){e.preventDefault&&e.preventDefault();var a=$(".wrap-listing-item .inner-wrap").outerWidth();detailListingWrap.css({right:-a+"px"}),form.af.filter(s.did).val(""),m2Map.pushState()},loading:function(e){m2Map.progressBar.show(),m2Map.loading_(e)},loading_:function(e){m2Map.progressBar.width(e+"%"),90>e&&(m2Map.loadingTimeout=setTimeout(function(){m2Map.loading_(e+10)},10*e))},loaded:function(){clearTimeout(m2Map.loadingTimeout),m2Map.progressBar.width("100%"),m2Map.loadingTimeout=setTimeout(function(){m2Map.progressBar.hide().width("0%")},150)},addDrawControl:function(){var e=document.createElement("div");e.className="draw-wrap",e.index=1;var a=document.createElement("a");a.className="button draw-button",a.innerHTML='<span class="icon-mv"><span class="icon-edit-copy-4"></span></span>Vẽ khoanh vùng';var t=document.createElement("a");t.className="button remove-button",t.innerHTML='<span class="icon-mv"><span class="icon-close-icon"></span></span>Xóa khoanh vùng',e.appendChild(a),e.appendChild(t),m2Map.map.controls[google.maps.ControlPosition.TOP_LEFT].push(e)}};form.af=$("#af-wrap").children(),form.afRect=form.af.filter(s.rect),form.afZoom=form.af.filter(s.z),form.afCenter=form.af.filter(s.c),form.projectInfoEl=$("#project-info"),form.formChange=function(e){var a=$(e.target);if(form.af.filter(s.rl).val(1),a.hasClass("search-item")){if(form.af.val(""),form.af.filter(s.rl).val(1),google.maps.event.removeListener(m2Map.boundsChangedEvent),m2Map.boundsChangedEvent=null,google.maps.event.removeListener(m2Map.zoomChangedEvent),m2Map.zoomChangedEvent=null,google.maps.event.addListenerOnce(m2Map.map,"bounds_changed",m2Map.setInitLocationProps),m2Map.removeAllDetail(),"project_building"==a.data("type")){form.projectInfoEl.html("");var t=a.data("id");$.get(loadProjectUrl,{id:t},function(e){form.projectInfoEl.html(e),toogleScroll()})}else form.projectInfoEl.html(""),toogleScroll();m2Map.changeLocation(function(e){m2Map.removeAreas(),m2Map.drawLocation(e),e.rl&&m2Map.drawList(e.rl)})}else if("order_by"==a.attr("id")){form.af.filter(s.ra).val(""),form.af.filter(s.raK).val(""),form.af.filter(s.page).val(""),m2Map.pushState();var o=form.fields.filter(s.rect);"city"==m2Map.currentDrawState||"district"==m2Map.currentDrawState||"ward"==m2Map.currentDrawState?o.prop("disabled",!0):form.af.filter(s.rm).val(1),form.af.filter(s.rl).val(1),m2Map.get(m2Map.drawDetailCallBack),o.prop("disabled",!1)}else{form.af.filter(s.rl).val(1),form.af.filter(s.page).val("");var o=form.fields.filter(s.rect);if("city"==m2Map.currentDrawState||"district"==m2Map.currentDrawState||"ward"==m2Map.currentDrawState){o.prop("disabled",!0),form.af.filter(s.rm).val("");var r=form.getFocusLocation(),n=m2Map.getZoomAreaLevel(m2Map.map.getZoom(),form.af.filter(s.iz).val(),r.type);form.af.filter(s.ra).val(n);var i=r.type==n?"id":r.type+"_id";form.af.filter(s.raK).val(i)}else form.af.filter(s.rm).val(1),form.af.filter(s.ra).val("");m2Map.get(function(e){m2Map.drawList(e.rl),e.rm&&(m2Map.removeAllDetail(),m2Map.drawDetail(e.rm)),e.ra&&m2Map.drawAreas(e.ra,n)}),o.prop("disabled",!1)}m2Map.pushState()},form.serialize=function(){return form.serialize_(form.fields)},form.serialize_=function(e){return e.filter(function(){return!!this.value}).serialize()},form.toggleConditionFields=function(){desktop.isDesktop()?form.af.prop("disabled",!1):form.af.prop("disabled",!0)},form.getFocusLocation=function(){var e={};return form.autoFill.each(function(){var a=$(this),t=a.val();t&&(e.id=t,e.type=a.attr("id").replace("_id",""))}),e},form.pagination=function(e){e.preventDefault(),form.af.filter(s.ra).val(""),form.af.filter(s.raK).val("");var a=Number($(this).data("page"))+1,t=form.af.filter(s.page);1==a?t.val(""):t.val(a),m2Map.pushState();var o=form.fields.filter(s.rect);("city"==m2Map.currentDrawState||"district"==m2Map.currentDrawState||"ward"==m2Map.currentDrawState)&&o.prop("disabled",!0),form.af.filter(s.rl).val(1),form.af.filter(s.rm).prop("disabled",!0),m2Map.get(form.paginationCallback),form.af.filter(s.rl).val(""),form.af.filter(s.rm).prop("disabled",!1),o.prop("disabled",!1)},form.paginationCallback=function(e){m2Map.drawList(e.rl)},form.itemClick=function(e){e.preventDefault();var a=$(this).data("id");m2Map.showDetail(a)},form.itemMouseEnter=function(e){var a=$(this).data("id");$.data(this,"mouseenterTimer",setTimeout(function(){var e=m2Map.getFocusMarker(a);e&&m2Map.focusMarker(e)},300))},form.itemMouseLeave=function(e){clearTimeout($.data(this,"mouseenterTimer"));var a=m2Map.getFocusMarker($(this).data("id"));a&&m2Map.setIcon(a,a.get("products").length,0)},events.attachDesktopEvent(form.fields,"change",form.formChange),events.attachDesktopEvent(form.listSearchEl,"click","a",form.formChange),events.attachDesktopEvent(contentHolder,"click",".pagination a",form.pagination),events.attachDesktopEvent(contentHolder,"click",".item a",form.itemClick),events.attachDesktopEvent($(".close-slide-detail"),"click",m2Map.closeDetail),events.attachDesktopEvent(contentHolder,"mouseenter",".item a",form.itemMouseEnter),events.attachDesktopEvent(contentHolder,"mouseleave",".item a",form.itemMouseLeave),events.attachDesktopEvent($window,"resize",form.toggleConditionFields);var InfoBox;form.toggleConditionFields(),desktop.loadedResource();