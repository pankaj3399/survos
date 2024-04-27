import {Controller} from '@hotwired/stimulus';

// https://javascript.plainenglish.io/12-best-practices-for-writing-asynchronous-javascript-like-a-pro-5ac4cb95d3c8
// now called from the TwigJsComponent Component, so it can pass in a Twig Template
// combination api-platform, inspection-bundle, dexie and twigjs
// loads data from API Platform to dexie, renders dexie data in twigjs

// import db from '../db.js';
import Twig from 'twig';
import Dexie from 'dexie';
import {stimulus_controller, stimulus_action, stimulus_target} from 'stimulus-attributes';

Twig.extend(function (Twig) {
    Twig._function.extend('stimulus_controller', (controllerName, controllerValues = {}, controllerClasses = {}, controllerOutlets = {} = {}) =>
        stimulus_controller(controllerName, controllerValues, controllerClasses, controllerOutlets)
    );
    Twig._function.extend('stimulus_target', (controllerName, r = null) => stimulus_target(controllerName, r));
    Twig._function.extend('stimulus_action', (controllerName, r, n = null, a = {}) => stimulus_action(controllerName, r, n, a));
});

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['content'];
    static values = {
        twigTemplate: String,
        twigTemplates: Object,
        refreshEvent: String,
        dbName: String,
        caller: String,
        // because passing an object is problematic if empty, just pass the config and parse it.
        // https://github.com/symfony/stimulus-bridge/issues/89
        config: Object,
        // schema: Object,
        // tableUrls: Object,
        version: Number,
        store: String,
        globals: Object,
        key: String, // overrides filter, get a single row.  ID is a reserved word!!
        filter: {
            type: String,
            default: '{}'
        }, // {status: 'queued'}
        // order: Object // e.g. {dateAdded: 'DESC'} (could be array?)
    }
    static outlets = ['app']; // could pass this in, too.

    connect() {
        // this.appOutlet.setTitle('test setTitle from appOutlet');
        // this.populateEmptyTables(db, this.configValue['stores']);

        // console.warn("hi from " + this.identifier + ' using dbName: ' + this.dbNameValue + '/' + this.storeValue);
        this.filter = this.filterValue ? JSON.parse(this.filterValue) : false;
        // console.error(this.callerValue, this.filterValue, this.filter);
        // compile the template

        let compiledTwigTemplates = {};
        for (const [key, value] of Object.entries(this.twigTemplatesValue)) {
            compiledTwigTemplates[key] = Twig.twig({
                data: value
            });
        }
        this.compiledTwigTemplates = compiledTwigTemplates;
        this.template = Twig.twig({
            data: this.twigTemplateValue
        });

        if (this.refreshEventValue) {
            document.addEventListener(this.refreshEventValue, (e => {
                console.log('i heard an event! ' + e.type);
                this.contentConnected();
            }));
        }
    }

    convertArrayToObject(array, key) {
        return array.reduce((acc, curr) => {
            acc[curr.name] = curr.schema;
            return acc;
        }, {});
    }

    initialize() {
        super.initialize();

        const db = new Dexie(this.dbNameValue);
        let schema = this.convertArrayToObject(this.configValue.stores);
        db.version(this.versionValue).stores(schema);
        db.open().then(db => {
            console.warn('db is now open');
            this.db = db;
            this.appOutlets.forEach(app => app.setDb(db));
            this.populateEmptyTables(this.db, this.configValue.stores);
            this.contentConnected();
        });
        // db.delete();
        // create the schema from the stores
        // https://dev.to/afewminutesofcode/how-to-convert-an-array-into-an-object-in-javascript-25a4
        // convert the survos_js_twig.yaml config['stores'] to an object for creating the tables

        // let schema2 = this.configValue.stores.reduce((acc, curr) => {
        //     acc[curr.name] = curr.schema;
        //     return acc;
        // });
        // console.error(schema);
        // return;
        // db.version(this.versionValue).stores(schema);
        // db.open();

    }

    appOutletConnected(app, body) {
        console.log(`${this.callerValue}: ${app.identifier}_controller is now connected to ` + this.identifier + '_controller');
        this.filter = this.appOutlet.getFilter(); // the global filter, like projectId

        // console.warn(this.hasAppOutlet, this.appOutlet.getCurrentProjectId());
        if (this.db) {
            this.appOutlet.setDb(this.db);
        } else {
            console.error('appOutletConnected, but db not yet set');
        }
        // console.log(app.identifier + '_controller', body);
        // console.error('page data', this.appOutlet.getProjectId);
    }

    async populateEmptyTables(db, stores) {
        console.error('populateEmptyTables')
        stores.forEach((store) => {
            let t = db.table(store.name);
            t.count(async c => {
                if (c > 0) {
                    console.error(store.name + ' has ' + c);
                    return;
                }
                const data = await loadData(store.url);
                console.error(data);
                // let withId = await data.map( (x, id) => {
                //     x.id = id+1;
                //     x.owned = id < 3;
                //     return x;
                // });
                // console.error(data, withId);

                await t.bulkPut(data)
                    .then((x) => console.log('bulk add', x))
                    .catch(e => console.error(e));
                // console.log ("Done populating.", data[1]);

            })
        })
    }

    // because this can be loaded by Turbo or Onsen
    async contentConnected() {
        // console.error(this.outlets);
        // this.outlets.forEach( (outlet) => console.warn(outlet));
        // if this is fired before the database is open, return, it'll be called later
        if (!this.db) {
            console.error('db is not connected');
            return;
        }
        let table = this.db.table(this.storeValue);

        if (this.hasAppOutlet) {
            // console.error(this.hasAppOutlet, this.appOutlet.getCurrentProjectId());
        }


        // if (this.filter) {
        //     this.filter = {'owned': true};
        //     table = table.where({owned: true}).toArray().then(rows => console.log(rows)).catch(e => console.error(e));
        // }
        // // console.log(table);
        // return;
        if (this.hasAppOutlet) {
            // this.appOutlet.setTitle('hello???');
            console.error(this.appOutlet.getFilter());
            // this.filter = this.appOutlet.getFilter();
        } else {
            // let appOutlet = document.getElementById('app_body').getAttribute('id');
            // appOutlet.setTitle('hello???');
            console.assert(this.hasAppOutlet, "missing appOutlet");
            return;
        }

        if (this.filter) {
            if (this.appOutlet.getFilter()) {
                this.filter = {...this.filter, ...this.appOutlet.getFilter(this.refreshEventValue)};
                console.error(this.filter);
            }
        } else {
            this.filter = this.appOutlet.getFilter(this.refreshEventValue);
        }
        console.error(this.filter);

        // this.appOutlet.setTitle('hello???!');
        if (this.keyValue) {

            console.error('page data', this.appOutlet.navigatorTarget.topPage.data);
            let key = this.appOutlet.navigatorTarget.topPage.data.id;
            console.error(this.appOutlet.navigatorTarget.topPage.data, key);
            table = table.get(parseInt(key));
            table
                .then(data => {
                    return {
                        content: this.template.render({data: data, globals: this.globalsValue}),
                        title: this.compiledTwigTemplates['title'].render({data: data, globals: this.globalsValue})
                    }

                })
                .then(({content, title}) => {
                    this.contentTarget.innerHTML = content
                    console.log(title);
                    if (this.hasAppOutlet) {
                        console.error(title);
                        this.appOutlet.setTitle(title);
                    }
                })
                .catch(e => console.error(e))
                .finally(e => console.log("finally rendered page"))

            return;

        } else if (this.filter) {

            table = table.filter(row => {

                // there's probably a way to use reduce() or something
                let okay = true;
                for (const [key, value] of Object.entries(this.filter)) {
                    // @todo: check for array and use 'in array'
                    okay = okay && (row[key] === value);
                    // console.log(`${key}: ${value}`, row[key] === value, okay);
                }
                return okay;
            });
        }

        // table.toArray().then( (data) => {
        //     data.forEach( (row) => {
        //         console.log(row);
        //         // nextPokenumber++;
        //     })
        // })

        table.toArray()
            .then(rows => this.template.render({rows: rows, globals: this.globalsValue}))
            .then(html => this.element.innerHTML = html);

    }
}

async function loadData(url) {
    console.log('fetching ' + url);
    const response = await fetch(url);
    const contentType = response.headers.get('Content-Type');
    // console.log(contentType, response.headers.forEach( (v,k) => console.log(k,v)));
    // @todo: fetch all pages
    // add the id!

    return await response.json().then(data => data['hydra:member'])
}

