"""Rebuild local DB content from the public pages of quadrant.ae (cached in ./live)."""
import re, os, json, glob, html, datetime, urllib.parse, requests
from bs4 import BeautifulSoup, Comment

PROJECT = r"D:\Quadrant Website\Quadrant-webiste-main\Quadrant-webiste-main"
LIVE = 'live'
S = requests.Session(); S.headers['User-Agent'] = 'Mozilla/5.0'

def soup(name):
    return BeautifulSoup(open(os.path.join(LIVE, name), encoding='utf-8').read(), 'html.parser')

def rel(url):
    """https://quadrant.ae/public/uploads/x.jpg -> uploads/x.jpg (path stored in DB, relative to /public)."""
    if not url: return None
    p = urllib.parse.unquote(urllib.parse.urlparse(url).path)
    return p.split('/public/', 1)[1] if '/public/' in p else None

missing = []
def ensure_file(url):
    r = rel(url)
    if not r: return None
    dest = os.path.join(PROJECT, 'public', *r.split('/'))
    if not os.path.exists(dest):
        resp = S.get(url.replace(' ', '%20'), timeout=120)
        if resp.status_code == 200 and resp.content:
            os.makedirs(os.path.dirname(dest), exist_ok=True)
            open(dest, 'wb').write(resp.content)
            missing.append(r)
        else:
            print('  !! could not download', url, resp.status_code)
    return r

def q(v):
    if v is None: return 'NULL'
    if isinstance(v, (int, float)): return str(v)
    return "'" + str(v).replace('\\', '\\\\').replace("'", "\\'") + "'"

def insert(table, row):
    cols = ', '.join(f'`{k}`' for k in row)
    return f"INSERT INTO `{table}` ({cols}) VALUES ({', '.join(q(v) for v in row.values())});"

sql = ['SET NAMES utf8mb4;', 'SET FOREIGN_KEY_CHECKS=0;', 'ALTER TABLE developments ADD COLUMN IF NOT EXISTS community_slug VARCHAR(255) NULL;']
for t in ['developments', 'development_images', 'communities', 'blog_posts', 'settings']:
    sql.append(f'TRUNCATE `{t}`;')

now = datetime.datetime(2026, 9, 30, 12, 0, 0)

# ---------------------------------------------------------------- communities
comm_order, comm_card = [], {}
for f in ['communities.html', 'communities_page=2.html']:
    s = soup(f)
    for a in s.select('a[href*="/communities/"]'):
        slug = a['href'].rstrip('/').split('/communities/')[-1]
        img = a.find('img') or (a.find_parent().find('img') if a.find_parent() else None)
        if slug and slug not in comm_order and img and 'uploads/communities' in img.get('src', ''):
            comm_order.append(slug); comm_card[slug] = img['src']

comm_slugs = json.load(open('slugs.json'))['communities']
communities = {}
for slug in comm_slugs:
    s = soup(f'communities__{slug}.html')
    name = s.find('h1').get_text(strip=True)
    banner = s.find('section', class_='inr-banner')
    banner_url = re.search(r"url\('([^']+)'\)", banner.get('style', '')).group(1) if banner else None
    wrap = s.find(class_='content-wrapper')
    desc, city = None, 'Dubai'
    if wrap:
        ps = wrap.find_all('p')
        text_ps = [p for p in ps if not p.find('img')]
        desc = '\n\n'.join(p.get_text(' ', strip=True) for p in text_ps) or None
        loc = [p for p in ps if p.find('img')]
        if loc: city = loc[0].get_text(strip=True) or 'Dubai'
    image = comm_card.get(slug) or (banner_url.replace('_banner_', '_') if banner_url else None)
    communities[slug] = dict(
        name=name, slug=slug, description=desc, city=city,
        image=ensure_file(image), banner_image=ensure_file(banner_url),
        sort_order=(comm_order.index(slug) + 1) if slug in comm_order else 100,
        is_active=1 if slug in comm_order or True else 0, is_featured=0,
        latitude=None, longitude=None, created_at=now, updated_at=now)

# ---------------------------------------------------------------- developments
dev_order = []
for f in ['buy.html', 'buy_page=2.html', 'buy_page=3.html']:
    for a in soup(f).select('a.qp-view-btn[href*="/buy/"]'):
        sl = a['href'].rstrip('/').split('/buy/')[-1]
        if sl not in dev_order: dev_order.append(sl)
cards = {}
for f in ['buy.html', 'buy_page=2.html', 'buy_page=3.html']:
    for card in soup(f).select('.qp-project-card'):
        a = card.select_one('a.qp-view-btn'); sl = a['href'].rstrip('/').split('/buy/')[-1]
        g = lambda c: (card.find(class_=c).get_text(' ', strip=True) if card.find(class_=c) else None)
        facts = [x.get_text(' ', strip=True).lstrip('•· ').strip() for x in card.select('.qp-project-facts span')]
        cards[sl] = dict(developer=g('qp-project-developer'), loc=g('qp-project-loc'), facts=facts)

featured = set(re.findall(r'quadrant\.ae/buy/([a-z0-9-]+)', open(os.path.join(LIVE, 'home.html'), encoding='utf-8').read()))
status_map = {'New Launch': 'launched', 'Under Construction': 'under_construction', 'Nearing Handover': 'completed'}
name_to_comm = {c['name'].lower(): sl for sl, c in communities.items()}

dev_slugs = json.load(open('slugs.json'))['buy']
dev_id = 0
for slug in sorted(dev_slugs, key=lambda x: dev_order.index(x) if x in dev_order else 999):
    s = soup(f'buy__{slug}.html')
    for c in s.find_all(string=lambda t: isinstance(t, Comment)): c.extract()
    ld = None
    for sc in s.find_all('script', type='application/ld+json'):
        try:
            j = json.loads(sc.string, strict=False)
            if j.get('@type') == 'Residence': ld = j
        except Exception: pass
    title = s.find('h1').get_text(strip=True)
    tag = s.find(class_='qp-pd-status-tag')
    status = status_map.get(tag.get_text(strip=True), 'launched') if tag else 'launched'
    facts = [p.get_text(' ', strip=True) for p in s.select('.qp-pd-fact .qp-fact-value')]
    price = float(ld['offers']['price']) if ld and ld.get('offers', {}).get('price') else None
    currency = ld['offers'].get('priceCurrency', 'AED') if ld else 'AED'
    yr = re.search(r'(20\d\d)', facts[1]) if len(facts) > 1 else None
    handover = f'{yr.group(1)}-12-31' if yr else None
    ptypes = facts[3] if len(facts) > 3 and facts[3] not in ('—', '-') else None
    # overview: section whose h2 is Overview, minus the "Community:" line
    overview, comm_name, pp, landmarks = None, None, None, None
    for sec in s.select('.qp-pd-grid .qp-pd-section'):
        h2 = sec.find('h2'); head = h2.get_text(strip=True) if h2 else ''
        if head == 'Overview':
            h2.extract()
            for p in sec.find_all('p'):
                if 'Community:' in p.get_text():
                    comm_name = p.get_text(' ', strip=True).split('Community:', 1)[1].strip(); p.extract()
            inner = sec.decode_contents().strip()
            overview = inner if '<' in inner else '<p>' + html.escape(inner, quote=False) + '</p>'
        elif head == 'Payment Plan':
            rows = []
            for tr in sec.select('tbody tr'):
                tds = [td.get_text(' ', strip=True) for td in tr.find_all('td')]
                if len(tds) >= 2: rows.append(f'{tds[1]} {tds[0]}'.strip() if tds[1] not in ('—', '') else tds[0])
            pp = '\n'.join(rows) or None
    lm = s.select('.qp-landmarks li')
    if lm:
        landmarks = '\n'.join(' — '.join(sp.get_text(strip=True) for sp in li.find_all('span') if sp.get_text(strip=True)) for li in lm)
    card = cards.get(slug, {})
    comm_name = comm_name or card.get('loc')
    comm_slug = name_to_comm.get((comm_name or '').lower())
    if not comm_slug and comm_name:  # community only referenced by name -> create it
        comm_slug = re.sub(r'[^a-z0-9]+', '-', comm_name.lower()).strip('-')
        communities[comm_slug] = dict(name=comm_name, slug=comm_slug, description=None, city='Dubai', image=None, banner_image=None,
                                      sort_order=200, is_active=0, is_featured=0, latitude=None, longitude=None, created_at=now, updated_at=now)
        name_to_comm[comm_name.lower()] = comm_slug
    ifr = s.find('iframe', src=lambda x: x and 'maps?q=' in x)
    if ifr and comm_slug:
        m = re.search(r'q=([-\d.]+),([-\d.]+)', ifr['src'])
        if m: communities[comm_slug]['latitude'], communities[comm_slug]['longitude'] = float(m.group(1)), float(m.group(2))
    hero = [img['src'] for img in s.select('img') if '/uploads/developments/' in img.get('src', '')]
    main_url = ld.get('image') if ld else (hero[0] if hero else None)
    gallery = [u for u in hero if '/gallery/' in u]
    brochure = s.find('a', href=lambda x: x and x.lower().endswith('.pdf') and '/uploads/' in x)
    bedroom = None
    for fct in card.get('facts', []):
        if re.search(r'\bBR\b|Bed', fct, re.I): bedroom = fct
    dev_id += 1
    pos = dev_order.index(slug) if slug in dev_order else 50
    sql.append(insert('developments', dict(
        id=dev_id, title=title, slug=slug, developer_name=card.get('developer'), community_slug=comm_slug,
        short_description=(ld or {}).get('description'), description=overview, property_types=ptypes, bedroom_range=bedroom,
        price_from=price, price_currency=currency, payment_plan=pp, handover_date=handover, nearby_landmarks=landmarks,
        main_image=ensure_file(main_url), brochure_pdf=ensure_file(brochure['href']) if brochure else None,
        status=status, is_featured=1 if slug in featured else 0, is_active=1,
        created_at=now - datetime.timedelta(hours=pos), updated_at=now)))
    for i, g in enumerate(gallery):
        sql.append(insert('development_images', dict(development_id=dev_id, image_path=ensure_file(g), sort_order=i, created_at=now, updated_at=now)))

# ---------------------------------------------------------------- communities SQL
for sl, c in communities.items():
    sql.append(insert('communities', c))
sql.append("UPDATE developments d JOIN communities c ON c.slug = d.community_slug SET d.community_id = c.id;")

# ---------------------------------------------------------------- insights
cat_map = {'Off-Plan': 'off-plan', 'Market Insights': 'market-insights', 'Communities': 'communities',
           'Buyer Guides': 'buyer-guides', 'Quadrant View': 'quadrant-view'}
for slug in json.load(open('slugs.json'))['insights']:
    s = soup(f'insights__{slug}.html')
    title = s.find('h1').get_text(strip=True)
    cat = s.find(class_='qp-cat'); cat = cat_map.get(cat.get_text(strip=True), cat.get_text(strip=True)) if cat else None
    by = s.find(class_='qp-byline'); byt = by.get_text(' ', strip=True) if by else ''
    author = byt.split('·')[0].strip() if '·' in byt else None
    dm = re.search(r'(\d{1,2} \w{3} \d{4})', byt)
    pub = datetime.datetime.strptime(dm.group(1), '%d %b %Y') if dm else now
    hero = s.select_one('.qp-article-hero img')
    body = s.find(class_='qp-article-body').decode_contents().strip()
    md = s.find('meta', attrs={'name': 'description'})
    sql.append(insert('blog_posts', dict(title=title, slug=slug, category=cat, author=author, body=body,
        excerpt=md['content'] if md else None, main_image=ensure_file(hero['src']) if hero else None,
        is_published=1, published_at=pub, created_at=pub, updated_at=pub)))

# ---------------------------------------------------------------- settings (from live JSON-LD + links)
home = soup('home.html')
org = None
for sc in home.find_all('script', type='application/ld+json'):
    try:
        j = json.loads(sc.string, strict=False)
        if j.get('@type') == 'RealEstateAgent': org = j
    except Exception: pass
wa = re.search(r'phone=(\d+)', str(home)) or re.search(r'wa\.me/(\d+)', str(home))
social = {k: next((u for u in org.get('sameAs', []) if k in u), None) for k in ['instagram', 'facebook', 'linkedin']}
settings = [
    ('general', 'site_name', 'Site Name', 'text', org['name'] if org else 'Quadrant'),
    ('general', 'site_logo', 'Site Logo', 'image', ensure_file(org['logo']) if org else None),
    ('contact', 'contact_phone', 'Phone', 'text', org.get('telephone')),
    ('contact', 'contact_email', 'Email', 'text', org.get('email')),
    ('contact', 'whatsapp_number', 'WhatsApp Number', 'text', '+' + wa.group(1) if wa else None),
    ('contact', 'office_address', 'Office Address', 'textarea', org['address']['streetAddress']),
    ('social', 'instagram_url', 'Instagram URL', 'text', social['instagram']),
    ('social', 'facebook_url', 'Facebook URL', 'text', social['facebook']),
    ('social', 'linkedin_url', 'LinkedIn URL', 'text', social['linkedin']),
]
sql.append("ALTER TABLE settings ADD COLUMN IF NOT EXISTS `label` VARCHAR(255) NULL, ADD COLUMN IF NOT EXISTS `type` VARCHAR(50) NULL DEFAULT 'text';")
for grp, key, label, typ, val in settings:
    if val: sql.append(insert('settings', {'group': grp, 'key': key, 'label': label, 'type': typ, 'value': val, 'created_at': now, 'updated_at': now}))

sql.append('SET FOREIGN_KEY_CHECKS=1;')
open('live_content.sql', 'w', encoding='utf-8').write('\n'.join(sql))
print(f"developments={dev_id} communities={len(communities)} statements={len(sql)} downloaded={len(missing)}")
for m in missing: print('  downloaded', m)
