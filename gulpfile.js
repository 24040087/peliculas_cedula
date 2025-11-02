import { src, dest, watch, series } from "gulp";
import postcss from "gulp-postcss";
import * as dartSass from "sass";
import gulpSass from "gulp-sass";
import terser from "gulp-terser";
import rename from "gulp-rename";
import concat from "gulp-concat";
import cssnano from "cssnano";

const sass = gulpSass(dartSass);

// ===== CSS =====
export function css(done) {
    src("src/css/**/*.css", { sourcemaps: true })
        .pipe(concat("styles.css")) // unir todos en uno
        .pipe(sass().on("error", sass.logError))
        .pipe(postcss([cssnano({ preset: "default" })]))
        .pipe(rename({ suffix: ".min" })) // styles.min.css
        .pipe(dest("static/css", { sourcemaps: "." }));
    done();
}

// ===== JS =====
export function js(done) {
    src("src/js/**/*.js", { sourcemaps: true })
        .pipe(concat("app.js")) // unir todos en uno
        .pipe(terser())
        .on("error", function (err) {
            console.error("Error in compressing JS:", err.toString());
        })
        .pipe(rename({ suffix: ".min" })) // app.min.js
        .pipe(dest("static/js", { sourcemaps: "." }));
    done();
}

// ===== Watch =====
export function watchFiles() {
    watch("src/css/**/*.css", css);
    watch("src/js/**/*.js", js);
}

export default series(js, css, watchFiles);