document.addEventListener("DOMContentLoaded", () => {
  const t = new Map(),
    e = "zolo-pagination-wrap";
  let a = null,
    n = null,
    o = !1;
  const s = async (n, s, r, i = !1) => {
      if (i && t.has(s)) {
        const e = t.get(s);
        return (
          (n.innerHTML = ""), n.insertAdjacentHTML("beforeend", e), void c()
        );
      }
      const l = (() => {
          const t = document.createElement("div");
          return (
            t.classList.add("preloader"),
            (t.innerHTML =
              '<div class="container"><div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div><div class="dot"></div></div>'),
            t
          );
        })(),
        d = n.querySelector(".zolo-post-timeline-grid"),
        p = n.querySelector(`.${e}`);
      d && d.appendChild(l);
      try {
        const e = new FormData();
        e.append("action", "zolo_ajax_post_pagination"),
          e.append("pageNumber", s),
          e.append("settings", r),
          e.append("zolo_nonce", zoloSettings.zolo_nonce);
        const o = await fetch(zoloSettings.ajaxurl, {
            method: "POST",
            body: e,
          }),
          l = await o.json();
        if (l.success) {
          if (
            (i && t.set(s, l.data),
            "number" === a &&
              ((n.innerHTML = ""),
              n.insertAdjacentHTML("beforeend", l.data),
              c()),
            "button" === a || "scroll" === a)
          ) {
            const t = document.createElement("div");
            t.innerHTML = l.data;
            const e = t.querySelectorAll(".zolo-item");
            e.length > 0
              ? e.forEach((t) => {
                  d.appendChild(t);
                })
              : (p.innerHTML = "");
          }
        } else console.error("Failed to fetch content:", l);
      } catch (t) {
        console.error("Error fetching content:", t);
      } finally {
        d && d.removeChild(l), (o = !1);
      }
    },
    c = () => {
      document.querySelectorAll(`.${e}`).forEach(p);
    },
    r = async (t) => {
      if ((t.preventDefault(), o)) return;
      o = !0;
      const c = t.target.getAttribute("href");
      if (!c) return;
      const r = ((t) =>
          t.includes("admin-ajax.php")
            ? t.match(/admin-ajax.*/)?.[0] || ""
            : t.match(/\/page\/\d+\//)?.[0] || "")(c),
        i = ((t) => {
          const e = t.match(/\d+/);
          return e ? parseInt(e[0], 10) : 1;
        })(r);
      (n = t.target.closest(`.${e}`).dataset.blockname),
        (a = t.target.closest(`.${e}`).dataset.paginationtype);
      const l = t.target.closest(`.wp-block-zolo-${n}`);
      if (l) {
        const t = l.dataset.attributes;
        await s(l, i, t, !0), (o = !1);
      }
    },
    i = (t, e) => {
      t.forEach((t) => t.addEventListener("click", e));
    },
    l = (t) => {
      const e = t.querySelector(".zolo-pagination-button");
      if (e) {
        let c = parseInt(e.dataset.pagenumber, 10) || 1;
        e.addEventListener("click", async (r) => {
          if ((r.preventDefault(), o)) return;
          (o = !0),
            e.classList.add("loading"),
            (c += 1),
            (a = t.dataset.paginationtype),
            (n = t.dataset.blockname);
          const i = r.target.closest(`.wp-block-zolo-${n}`);
          if (i) {
            const t = i.dataset.attributes;
            await s(i, c, t), (o = !1), e.classList.remove("loading");
          }
        });
      }
    },
    d = (t) => {
      let e = parseInt(t.dataset.pagenumber, 10) || 1;
      (a = t.dataset.paginationtype), (n = t.dataset.blockname);
      const c = t.dataset.totalpage;
      window.addEventListener("scroll", () => {
        if (
          !o &&
          window.scrollY + window.innerHeight >=
            document.documentElement.offsetHeight - 200
        ) {
          const a = t.closest(`.wp-block-zolo-${n}`);
          if (a) {
            const t = a.dataset.attributes;
            (e += 1), (o = !0), c >= e && s(a, e, t);
          }
        }
      });
    };
  function p(t) {
    switch (((a = t.dataset.paginationtype), (n = t.dataset.blockname), a)) {
      case "normal":
        break;
      case "number":
        i(t.querySelectorAll("a"), r);
        break;
      case "button":
        l(t);
        break;
      case "scroll":
        d(t);
    }
  }
  document.querySelectorAll(`.${e}`).forEach(p);
});
