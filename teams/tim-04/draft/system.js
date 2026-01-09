/**
 * Liangpran Enterprise System Core
 * Architecture: LocalStorage Mock DB + Simulated API
 * Version: 4.0.0 (Enterprise)
 */

const System = (function () {
    const DB_KEY = 'liangpran_ent_db_v4';
    const SESSION_KEY = 'liangpran_ent_session_v4';

    // --- SEED DATA (Realistic Mahakam Ulu Content) ---
    const seedData = {
        meta: { version: '4.0.0', created_at: Date.now() },
        users: [
            { id: 1, username: 'admin', password: '123', name: 'Chief Ranger', role: 'super_admin', avatar: 'https://ui-avatars.com/api/?name=Admin&background=2D3A3A&color=fff' },
            { id: 2, username: 'ranger', password: '123', name: 'Field Officer', role: 'ranger', avatar: 'https://ui-avatars.com/api/?name=Ranger&background=8C7E6A&color=fff' }
        ],
        // 1. Destinations (Core Spots)
        destinations: [
            {
                id: 'DST-001',
                name: 'Puncak Liangpran',
                type: 'Mountain Peak',
                desc: 'Titik tertinggi dengan pemandangan awan abadi. Saksi bisu sejarah geologis Kalimantan.',
                elevation: '2240 mdpl',
                status: 'Open',
                image: './assets/hero-mountain.png'
            },
            {
                id: 'DST-002',
                name: 'Hutan Lumut',
                type: 'Forest Zone',
                desc: 'Zona ekosistem purba di ketinggian 1800mdpl, didominasi lumut tebal dan anggrek epifit.',
                elevation: '1800 mdpl',
                status: 'Restricted',
                image: './assets/nature-forest.png'
            }
        ],
        // 2. Trails (Routes)
        trails: [
            {
                id: 'TRL-001',
                name: 'Jalur Leluhur (Ancestral Path)',
                difficulty: 'Hard',
                distance: '12 km',
                duration: '8-10 Hours',
                features: ['River Crossing', 'Steep Climb', 'High Biodiversity'],
                status: 'Open'
            },
            {
                id: 'TRL-002',
                name: 'River Side Trek',
                difficulty: 'Easy',
                distance: '3 km',
                duration: '2 Hours',
                features: ['Waterfalls', 'Bird Watching', 'Family Friendly'],
                status: 'Open'
            }
        ],
        // 3. Biodiversity (Flora & Fauna)
        biodiversity: [
            {
                id: 'BIO-001',
                name: 'Rangkong Gading',
                scientific: 'Rhinoplax vigil',
                type: 'Fauna',
                status: 'Critically Endangered',
                desc: 'Burung keramat masyarakat Dayak, simbol kepemimpinan dan kesucian.',
                image: 'https://cdn.betahita.id/6/0/8/5/6085.jpg'
            },
            {
                id: 'BIO-002',
                name: 'Anggrek Hitam',
                scientific: 'Coelogyne pandurata',
                type: 'Flora',
                status: 'Protected',
                desc: 'Maskot flora Kalimantan Timur, terkenal dengan lidah hitamnya yang eksotis.',
                image: 'https://mentarivillage.com/wp-content/uploads/2025/08/Coelogyne_pandurata_orchid.jpg'
            },
            {
                id: 'BIO-003',
                name: 'Macan Dahan',
                scientific: 'Neofelis diardi',
                type: 'Fauna',
                status: 'Vulnerable',
                desc: 'Predator puncak hutan Kalimantan, ahli memanjat yang sulit dipahami.',
                image: 'https://upload.wikimedia.org/wikipedia/commons/2/2d/CloudedLeopard.jpg'
            }
        ],
        // 4. Articles
        articles: [
            {
                id: 'ART-001',
                title: 'Menjaga Jantung Borneo',
                author: 'Tim Konservasi',
                date: '2026-01-08',
                summary: 'Upaya pelestarian hutan adat melalui program ekowisata berbasis komunitas.'
            }
        ],
        // 5. Visitor Analytics (Mock Logs)
        visitors_logs: [
            { date: '2026-01-01', count: 45 },
            { date: '2026-01-02', count: 52 },
            { date: '2026-01-03', count: 38 },
            { date: '2026-01-04', count: 65 },
            { date: '2026-01-05', count: 72 },
            { date: '2026-01-06', count: 89 },
            { date: '2026-01-07', count: 95 }
        ]
    };

    // --- CORE DATALAYER ---
    function loadDB() {
        const data = localStorage.getItem(DB_KEY);
        if (!data) {
            localStorage.setItem(DB_KEY, JSON.stringify(seedData));
            return seedData;
        }
        return JSON.parse(data);
    }

    function saveDB(data) {
        localStorage.setItem(DB_KEY, JSON.stringify(data));
    }

    // --- API INTERFACE ---
    return {
        // Database Access
        db: {
            get: (table) => {
                const db = loadDB();
                if (!db[table]) {
                    // Auto-seed if table missing (migration simulation)
                    db[table] = seedData[table] || [];
                    saveDB(db);
                }
                return db[table] || [];
            },
            find: (table, id) => {
                const list = loadDB()[table] || [];
                return list.find(x => x.id == id);
            },
            insert: (table, item) => {
                const db = loadDB();
                if (!db[table]) db[table] = [];
                item.id = item.id || 'GEN-' + Date.now();
                item.created_at = new Date().toISOString();
                db[table].unshift(item);
                saveDB(db);
                return item;
            },
            update: (table, id, updates) => {
                const db = loadDB();
                const idx = db[table].findIndex(x => x.id == id);
                if (idx > -1) {
                    db[table][idx] = { ...db[table][idx], ...updates, updated_at: new Date().toISOString() };
                    saveDB(db);
                    return db[table][idx];
                }
                return null;
            },
            remove: (table, id) => {
                const db = loadDB();
                const initialLen = db[table].length;
                db[table] = db[table].filter(x => x.id != id);
                saveDB(db);
                return db[table].length < initialLen;
            },
            reset: () => {
                localStorage.removeItem(DB_KEY);
                window.location.reload();
            }
        },

        // Analytics Helper
        analytics: {
            getVisitorStats: () => {
                const logs = loadDB().visitors_logs || [];
                return {
                    labels: logs.map(l => l.date),
                    data: logs.map(l => l.count),
                    total: logs.reduce((a, b) => a + b.count, 0)
                };
            }
        },

        // Auth Logic
        auth: {
            login: (u, p) => {
                const db = loadDB();
                const user = db.users.find(x => x.username === u && x.password === p);
                if (user) {
                    const session = { ...user, token: 'jwt_mock_' + Date.now() };
                    localStorage.setItem(SESSION_KEY, JSON.stringify(session));
                    return { success: true, session };
                }
                return { success: false };
            },
            logout: () => {
                localStorage.removeItem(SESSION_KEY);
                window.location.href = 'admin.html';
            },
            user: () => JSON.parse(localStorage.getItem(SESSION_KEY)),
            check: () => !!localStorage.getItem(SESSION_KEY)
        }
    };
})();
