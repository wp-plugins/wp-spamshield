<?php
if ( !function_exists( 'add_action' ) ) {
	if ( !headers_sent() ) { header('HTTP/1.1 403 Forbidden'); }
	die('ERROR: This plugin requires WordPress and will not function if called directly.');
	}

function spamshield_get_anchortxt_blacklist() {
	$blacklist_keyphrases = array( // 669
		"1 night stand", "1 nite stand", "2013", "2014", "2015", "access", "accident", "account", "accountant", "accounting", "accutane", "acomplia", "action", "acupunture", "adipex", "administration", "advertise", 
		"advertising", "affiliate", "african", "alabama", "alaska", "allied", "alprostadil", "amature", "ambassador", "android", "annual", "anonymous", "anxiety", "application", "arcane", "argent", "arizona", 
		"arkansas", "asphalt", "attorney", "auction", "aumento", "avanafil", "avenue", "average", "aviator", "balance", "bamboo", "bandage", "bankruptcy", "basement", "battery", "bed bug", "beginner", "behavior", 
		"bespoke", "bestiality", "betting", "biggest", "birthday", "bisexual", "blackjack", "blogging", "blow job", "bluetooth", "booster", "booty call", "boutique", "bracelet", "brazilian", "bridal", "browse", 
		"bucuresti", "builder", "building", "bungalow", "business", "buy pill", "caffeine", "calculator", "california", "call girl", "cambogia", "cannabis", "capital", "car rental", "cash advance", "casinos", 
		"casual", "celebrity", "cellulite", "celulit", "chat room", "chaturbate", "cheap", "cheat", "chicago", "children", "chiropractic", "chiropractor", "cialis", "cigarette", "classic", "cleaning", "cleanse", 
		"clearance", "click here", "clinic", "clinical", "clitoris", "clonazepam", "clothing", "clutch", "college student", "collision", "coming", "command", "comment poster", "comments", "commercial", "commission", 
		"compact", "compagnie", "company", "compilation", "computer", "concrete", "condition", "conditioning", "configure", "connecticut", "consolidation", "constructeur", "construction", "consultant", "consulting", 
		"content", "contracting", "contractor", "control", "convoy", "cosmetic", "counseling", "country", "county", "coupon", "crack", "credit card", "cuisine", "cum", "cum shot", "custom", "customer", 
		"dailymotion", "dapoxetine", "dating", "definition", "dentist", "dermatologist", "desert", "design", "desnuda", "detroit", "deutschland", "development", "diet pill", "diets", "difference", "different", 
		"dildo", "diminish", "discount", "disfuncao", "dissertation", "domain", "dosage", "download", "drawing", "dresses", "drug rehab", "dui lawyer", "durable", "dysfunction", "e-learning", "earn money", 
		"educational", "ejaculate", "electrician", "electronic", "elixir", "emergency", "empire", "employment", "engine", "engrossar", "enhancement", "enlargement", "entertaining", "entertainment", "ephedra", 
		"ephedrine", "erectile", "erection", "eretil", "erotic", "eroticism", "escort service", "escorts", "european", "evasion", "executive", "exercise", "exterminating", "extermination", "exterminator", "extract", 
		"extreme", "eyeglasses", "fabric", "facebook", "factory", "family", "fasix", "fat loss", "female", "feminine", "financial", "finishing", "flooring", "follower", "following", "for sale", "foreclosure", 
		"forever", "forex", "forgetfulness", "formula", "fort worth", "free code", "frontier", "frozen", "fuck", "fuckbuddy", "fuckin", "furniture", "galaxy", "gambling", "gambling online", "gaming", "garcinia", 
		"garcinia cambogia", "generation", "generator", "genesis", "get rid of", "glazing", "government", "gratis", "gratuit", "green coffee", "green tea", "guarantee", "gynecologist", "gynecology", "hack wifi", 
		"hair loss", "hand bag", "hawaii", "health", "health care", "healthy", "hearthstone", "heating", "heavenly", "hentai", "herbalife", "herpes", "heterosexual", "hitherto", "home design", "homeopathic", 
		"homepage", "homosexual", "hormone", "hospital", "hosting", "hotel", "how to", "illinois", "impotencia", "incest", "india outsource", "indiana", "indianapolis", "infection", "infestation", "information", 
		"inhibitor", "injury", "injury lawyer", "injustice", "instagram", "installation", "installer", "instant", "insurance", "international", "internet", "interview", "italian", "jacksonville", "jailbreak", 
		"javascript", "jewelry", "johannesburg", "junction", "jungle", "kentucky", "keratin", "ketone", "keyword", "klonopin", "kroatien insel brac", "labia", "labial", "labiale", "laptop", "laptopuri", "las vegas", 
		"legend", "leveling", "levelling", "levitra", "levtira", "libido", "library", "limited", "link builder", "link building", "linked in", "locksmith", "logo design", "los angeles", "lose fat", "lose weight", 
		"losing", "louisiana", "louisville", "lunette", "lyric", "machine", "management", "marijuana", "massachusetts", "massage", "masturbate", "medical", "medication", "medium", "megapolis", "memphis", "message", 
		"michigan", "milf", "minnesota", "mississippi", "missouri", "mistake", "mobilabonnement priser", "mobile", "modern", "modernity", "modulesoft", "monster", "montaigne", "montre", "mortal", "movie", "moving", 
		"muscle", "muscular", "naked", "nashville", "natural", "nebraska", "negotiation", "network", "nevada", "new hampshire", "new jersey", "new mexico", "new york", "north carolina", "north dakota", "nude", 
		"nudism", "numerology", "nursery", "ob-gyn", "obstetric", "obstetrician", "office", "oklahoma", "one night stand", "one nite stand", "online", "online gambling", "online marketing", "opiate", "optimization", 
		"organization", "orgasm", "outfit", "outlet", "outsource india", "oxymoron", "page rank", "particular", "password", "pavilion", "payday", "penetracion", "penetrate", "peniana", "peniano", "penile", "penis", 
		"pennsylvania", "perfectly", "periodontist", "personalization", "pest control", "petroleum", "phantom", "pharmacy", "phentermine", "philadelphia", "photoshop", "php expert", "phpdug", "phytoceramide", 
		"pills", "pinterest", "plague", "plan cul", "plan q", "plantar fasciitis", "plastic", "platinum", "plumbing", "policy", "politic", "political", "porn", "porn star", "porno", "pornographic", "pornography", 
		"porntube", "portable", "power kite", "prelude", "premature", "premium", "prepaid", "prescription", "previous", "priligy", "primary", "prisoner", "product", "production", "professional", "project", 
		"promo code", "promotion", "propane", "propecia", "property", "prostitute", "protein", "proxy", "psychic", "psychics", "purse", "pussy", "quartz", "racing", "ranking", "rapes", "raping", "rapist", 
		"reaction", "realistic", "reality", "realty", "recruiting", "redeem", "reduce", "reflexion", "relate", "release", "religion", "removal", "remove", "renewal", "renovating", "renovation", "rent a car", 
		"rental", "repair", "reparatii", "repellent", "replica", "reputation", "restoration", "restore", "revatio", "reverse", "reviews", "rhinoplasty", "rhode island", "rimonabant", "ripped", "rivotril", "router", 
		"safety", "san antonio", "san diego", "san francisco", "san jose", "search engine", "search marketing", "seattle", "secondary", "secret", "segment", "sem", "seminar", "seo", "septum", "services", "sex", 
		"sex drive", "sex tape", "sexcam", "sexe", "sexologia", "sexual", "sexual performance", "sexual services", "sexy", "shades", "shampoo", "shipping", "short-term loan", "sildenafil", "sister", "skater", 
		"smoking", "sneaker", "social", "social bookmark", "social media", "social poster", "social submitter", "software", "soma", "south carolina", "south dakota", "special", "spence diamond", "spinal", 
		"spray tan", "starcraft", "staxyn", "stendra", "steroid", "streaming", "student loan", "submitter", "success", "suggestion", "sunglasses", "supplement", "support", "surgeons", "surgery", "survey", "swatch", 
		"sweating", "system", "tablet", "tactic", "tadalafil", "tanning", "tassel", "technology", "template", "testosterone", "therapy", "title", "trackback", "tractor", "trading", "traffic", "training", "tramadol", 
		"transportation", "travel", "treatment", "troubleshoot", "troubleshooting", "turbo tax", "turquoise", "twitter", "tycoon", "ultimate", "unblocked", "underneath", "underwear", "unique", "united states", 
		"university", "unlimited", "unlock", "uptown", "utility", "vagina", "vaginal", "valium", "vancouver", "vardenafil", "vehicle", "ventilation", "veterinarian", "veterinary", "viagra", "video", "videography", 
		"vigara", "vigrx", "village", "vintage", "visit now", "volume", "voucher", "warfare", "watches", "wayfarer", "web host", "web hosting", "web page", "web site", "webmaster", "weight loss", "weightless", 
		"west virginia", "wholesale", "wicked", "wifi hack", "wifi hacker", "wisconsin", "wrestling", "writing service", "wyoming", "xanax", "xxx", "youtube", "zimulti", "zithromax", "zoekmachine optimalisatie", 
		);
	return $blacklist_keyphrases;
	}
?>