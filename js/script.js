var version = "";
$(document).ready(function () {
  try {
    if (typeof (Storage) !== "undefined") {
      if (typeof localStorage["versionInfo-minZIP"] == "undefined") {
        setVersion();
        localStorage["versionInfo-minZIP"] = version;
      }
      if (localStorage["versionInfo-minZIP"] == "yui") {
        $('#version').prop('checked', false);
      } else {
        $('#version').prop('checked', true);
      }
    }
  } catch (e) {
    //console.log("LocalStorage not Suported");
  }
  //extensionFlag
  try {
    if (typeof (Storage) !== "undefined") {
      if (typeof localStorage["versionInfo-minExt-minZIP"] == "undefined") {
        localStorage["versionInfo-minExt-minZIP"] = $("#extensionFlag").prop("checked");
      }
      if (localStorage["versionInfo-minExt-minZIP"] == "true") {
        $("#extensionFlag").prop("checked", true)
      } else {
        $("#extensionFlag").prop("checked", false);
      }
    }
  } catch (e) {
    //console.log("LocalStorage not Suported");
  }
  $('#version').on("change", function () {

    setVersion();
    try {
      if (typeof (Storage) !== "undefined") {
        localStorage["versionInfo-minZIP"] = version;
      }
    } catch (e) {
      //console.log("LocalStorage not Suported");
    }
  });
  $('#extensionFlag').on("change", function () {

    try {
      if (typeof (Storage) !== "undefined") {
        localStorage["versionInfo-minExt-minZIP"] = $("#extensionFlag").prop("checked");
      }
    } catch (e) {
      //console.log("LocalStorage not Suported");
    }
  });
  $('form input').change(function () {
    $('form p').text(this.files.length + " " + (this.files.length > 1 ? "files" : "file") + " selected.");
    $("form #upload").addClass("pulse");
  });
  $("form").on("click", "#download", function (event) {
    window.location = $("#download").attr("data-href");
    $("#download").removeClass("pulse").attr("data-href", window.location.pathname + "zip");
    setTimeout(
      function () {
        location.reload();
      }, 10000);
  });
  $(".loading").mousedown(function () {
    return false;
  });
  setVersion();
});
$(function () {
  /* variables */
  var status = $('.status');
  var determinate = $(".determinate");

  /* submit form with ajax request using jQuery.form plugin */
  $('form').ajaxForm({

    /* set data type json */
    dataType: 'json',

    /* reset before submitting */
    beforeSend: function () {
      status.fadeOut();
      determinate.css("width", "0%");
    },

    /* progress bar call back*/
    uploadProgress: function (event, position, total, percentComplete) {
      var pVel = percentComplete + '%';
      determinate.css("width", pVel);
    },

    /* complete call back */
    complete: function (data) {
      if (typeof data.responseJSON == "undefined") {
        alert("Minification Unsuccessful.. Please Upload Files(ZIP/JS/CSS only.)");
        location.reload();
      }
      $(".loading").removeClass("hide");
      minify();
    }
  });
});
function setVersion() {
  if ($('#version').prop('checked')) {
    version = "uglify";
    $("body").css("background", "rgba(0, 0, 0, 0) linear-gradient(0deg, white, rgb(0, 137, 123) 0%) repeat scroll 0% 0% / auto padding-box border-box");
  } else {
    version = "yui";
    $("body").css("background", "rgba(0, 0, 0, 0) linear-gradient(0deg, white, rgb(0, 137, 123) 80%) repeat scroll 0% 0% / auto padding-box border-box");
  }
}
function minify() {
  setVersion();
  $.ajax({
    type: "POST",
    url: 'executeFunction.php',
    dataType: 'json',
    data: { functionname: 'minify', version: version, extensionflag: $("#extensionFlag").prop("checked") },

    success: function (obj, textstatus) {
      $(".loading").addClass("hide");
      if (obj.result != "" && textstatus == "success" && obj.result.indexOf("@$@") > -1) {
        var ress = obj.result.split('@$@');
        $(".formDiv").css("max-height", $(".formDiv").outerHeight(true) + "px");
        $(".formDiv").css("height", $(".formDiv").outerHeight(true) + "px");
        $(".formDiv p").css("line-height", "initial");
        $("input").hide();
        $(".formDiv p").addClass("nonMarginP");
        $(".formDiv p").text("");
        $(".formDiv p").append(ress[0]);
        $("form #upload").hide();
        $("form").append("<a id=\"download\" class=\"btn-floating halfway-fab waves-effect waves-green pulse red\" data-href=\"" + ress[1] + "\"><i class=\"material-icons\">get_app</i></a>");
      } else {
        alert("Minification Unsuccessful");
        location.reload();
      }
    }
  });
}