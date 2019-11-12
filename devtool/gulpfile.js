const gulp = require("gulp");
const sass = require("gulp-sass");
const plumber = require("gulp-plumber");
const sourcemaps = require("gulp-sourcemaps");
const progeny = require("gulp-progeny"); // cssの差分ビルドのため
const browserSync = require("browser-sync").create();


gulp.task("sass", function() {
  return gulp
    .src("../html/src/scss/bundle.scss")
    .pipe(plumber())
    .pipe(progeny())
    .pipe(sourcemaps.init())
    .pipe(
      sass({
        outputStyle: "expanded"
      })
    )
    .pipe(sourcemaps.write())
    .pipe(gulp.dest("../html/dist/css/"))
});


gulp.task("server", function(done) {
  browserSync.init({
    // server: {
    //   // サーバの立ち上がる位置
    //   baseDir: "../",
    //   index: "/html/index.php"
    // }
    proxy: "http://nude.com",
    reloadOnRestart: true,
  });
  done();
  console.log("+------------------+ server was launched +------------------+");
});


gulp.task("watch", function(done) {
  gulp.watch("../html/src/scss/*.scss", gulp.task("sass"));
  gulp.watch("../html/dist/css/*.css", gulp.task("server"));
  gulp.watch("../html/*/*.html", gulp.task("server"));
  gulp.watch("../html/dist/js/*.js", gulp.task("server"));
  gulp.watch("../")
  .on("change", function() {
    browserSync.reload();
  });
  done();
  console.log("+------------------+ Gulp watch start +------------------+");
});


gulp.task("default", gulp.parallel("server", "watch"));