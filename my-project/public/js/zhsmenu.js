class zhsmenu {
    menu = {};

    constructor(menu) {
        this.menu = menu;
    }

    init(selector) {
        let menu = this.menu;
        let getParrent = (node, prev) => {
            node.parrent = prev?.title;
            node?.childrens.forEach((item) => {
                getParrent(item, node);
            })
        };
        getParrent(menu);

        let html = `<div class="zhs-menu">
                <div class="zhs-menu--items">`;
        let t = [];
        let cLvl = 1;
        let queue = [...menu.childrens];
        while (queue.length) {
            let item = queue.shift();
            let displaynone = (cLvl > 1) ? 'display:none' : ''
            let next = (item.childrens.length) ? `<img style="width:10px;" src="arrow.svg">` : "";
            let url = item?.url?.length ? item?.url : '#';
            html += `
                <div lvl="${cLvl}" data="${item.title}" parrent="${item.parrent}" style="min-width:180px;${displaynone}" class="zhs-menu--items--item">
                    <a style="display:flex;" href="${url}">
                        ${item.title}
                        <span style="margin-left:auto;">${next}</span>
                    </a>
                </div>
                `;
            t.push(...item.childrens);
            if (!queue.length) {
                queue.push(...t);
                html += `</div>`;
                if (t.length) {
                    html += `<div class="zhs-menu--items">`;
                }
                t = [];
                cLvl++
            }
        }
        let maxLvl = cLvl;
        html += "</div>"
        document.querySelector(selector).innerHTML = html;
        document.querySelectorAll(".zhs-menu--items--item").forEach((el) => {
            el.addEventListener("mousemove", (item) => {
                let data = el.getAttribute("data");
                document.querySelectorAll(`[parrent="${data}"]`)[0].parentElement.style.marginTop = (el.offsetTop) + "px";
                let lvl = document.querySelectorAll(`[parrent="${data}"]`)[0].getAttribute("lvl");

                for (let i = parseInt(lvl); i <= maxLvl; i++) {
                    document.querySelectorAll(`[lvl="${i}"]`).forEach((el) => {
                        el.style.display = "none";
                    });
                }
                document.querySelectorAll(`[parrent="${data}"]`).forEach((el) => {
                    el.style.display = "inline";
                })
            });
        })
    }
}
