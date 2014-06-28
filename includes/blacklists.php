<?php
if ( !function_exists( 'add_action' ) ) {
	if ( !headers_sent() ) { header('HTTP/1.1 403 Forbidden'); }
	die('ERROR: This plugin requires WordPress and will not function if called directly.');
	}

function spamshield_get_anchortxt_blacklist() {
	$blacklist_keyphrases = array( // 502
		"2013", "2014", "2015", "access", "accident", "account", "accountant", "accounting", "accutane", "acomplia", "action", "adipex", "administration", "advertise", "advertising", "affiliate", "alabama", 
		"alaska", "alprostadil", "amature", "android", "anonymous", "application", "arizona", "arkansas", "attorney", "avanafil", "average", "aviator", "balance", "bamboo", "bandage", "bankruptcy", "basement", 
		"beginner", "behavior", "bespoke", "bestiality", "betting", "biggest", "birthday", "bisexual", "blackjack", "blow job", "bluetooth", "boutique", "brazilian", "bridal", "bucuresti", "builder", "building", 
		"business", "buy pill", "caffeine", "calculator", "california", "call girl", "cambogia", "cannabis", "car rental", "cash advance", "casinos", "celebrity", "chat room", "cheap", "cheat", "chicago", 
		"children", "chiropractic", "chiropractor", "cialis", "cigarette", "classic", "cleaning", "cleanse", "click here", "clinic", "clitoris", "clonazepam", "clothing", "clutch", "college student", "collision", 
		"coming", "comment poster", "comments", "commercial", "commission", "compact", "compagnie", "company", "compilation", "computer", "concrete", "configure", "connecticut", "consolidation", "constructeur", 
		"construction", "consultant", "consulting", "contracting", "contractor", "control", "cosmetic", "country", "county", "coupon", "crack", "credit card", "cuisine", "cum", "cum shot", "custom", "customer", 
		"dailymotion", "dapoxetine", "dentist", "design", "desnuda", "detroit", "development", "diet pill", "diets", "dildo", "discount", "dissertation", "domain", "dosage", "download", "drug rehab", "dui lawyer", 
		"durable", "dysfunction", "e-learning", "earn money", "educational", "ejaculate", "electronic", "emergency", "empire", "employment", "engine", "enhancement", "enlargement", "ephedra", "ephedrine", 
		"erectile", "erection", "erotic", "eroticism", "escort service", "european", "evasion", "executive", "exercise", "exterminator", "extract", "extreme", "eyeglasses", "fabric", "facebook", "female", 
		"feminine", "financial", "flooring", "follower", "following", "for sale", "foreclosure", "forever", "forex", "formula", "fort worth", "free code", "frontier", "frozen", "fuck", "fuckbuddy", "fuckin", 
		"furniture", "galaxy", "gambling", "gambling online", "gaming", "garcinia", "garcinia cambogia", "generation", "generator", "genesis", "get rid of", "glazing", "gratis", "gratuit", "green coffee", 
		"green tea", "guarantee", "hand bag", "hawaii", "health", "health care", "hearthstone", "heating", "heavenly", "hentai", "herbalife", "heterosexual", "hitherto", "home design", "homeopathic", "homepage", 
		"homosexual", "hormone", "hosting", "hotel", "how to", "illinois", "incest", "india outsource", "indiana", "indianapolis", "infection", "infestation", "information", "inhibitor", "injury lawyer", 
		"injustice", "instagram", "installation", "installer", "instant", "insurance", "international", "internet", "interview", "jacksonville", "javascript", "johannesburg", "junction", "jungle", "kentucky", 
		"keratin", "ketone", "klonopin", "kroatien insel brac", "laptop", "laptopuri", "las vegas", "legend", "leveling", "levelling", "levitra", "levtira", "libido", "library", "link builder", "link building", 
		"logo design", "los angeles", "lose weight", "louisiana", "louisville", "lunette", "machine", "management", "marijuana", "massachusetts", "massage", "medical", "medication", "memphis", "message", "michigan", 
		"minnesota", "mississippi", "missouri", "mistake", "mobilabonnement priser", "modern", "modulesoft", "monster", "montaigne", "mortal", "movie", "moving", "muscle", "naked", "nashville", "natural", 
		"nebraska", "nevada", "new hampshire", "new jersey", "new mexico", "new york", "north carolina", "north dakota", "nude", "nudism", "numerology", "nursery", "oklahoma", "online", "online gambling", 
		"online marketing", "opiate", "optimization", "organization", "orgasm", "outlet", "outsource india", "particular", "password", "payday", "penis", "pennsylvania", "periodontist", "personalization", 
		"petroleum", "pharmacy", "phentermine", "philadelphia", "photoshop", "php expert", "phpdug", "plague", "plantar fasciitis", "plastic", "platinum", "plumbing", "politic", "political", "porn", "porno", 
		"pornographic", "pornography", "porntube", "portable", "power kite", "prelude", "premature", "premium", "prepaid", "prescription", "previous", "priligy", "primary", "prisoner", "product", "project", 
		"promo code", "promotion", "propane", "propecia", "property", "prostitute", "protein", "proxy", "psychic", "pussy", "racing", "ranking", "rapes", "raping", "rapist", "reality", "realty", "redeem", 
		"reflexion", "release", "removal", "remove", "renewal", "renovating", "renovation", "rent a car", "rental", "repair", "reparatii", "repellent", "replica", "restoration", "restore", "revatio", "reviews", 
		"rhinoplasty", "rhode island", "rimonabant", "ripped", "rivotril", "router", "safety", "san antonio", "san diego", "san francisco", "san jose", "search engine", "search marketing", "seattle", "secondary", 
		"secret", "sem", "seminar", "seo", "septum", "services", "sex", "sex drive", "sex tape", "sexe", "sexual", "sexual performance", "sexual services", "sexy", "shampoo", "shipping", "short-term loan", 
		"sildenafil", "sneaker", "social", "social bookmark", "social media", "social poster", "social submitter", "software", "soma", "south carolina", "south dakota", "spence diamond", "spinal", "staxyn", 
		"stendra", "steroid", "streaming", "student loan", "submitter", "success", "sunglasses", "supplement", "support", "surgeons", "surgery", "survey", "sweating", "tablet", "tactic", "tadalafil", "tanning", 
		"technology", "template", "testosterone", "therapy", "title", "trackback", "tractor", "trading", "tramadol", "transportation", "travel", "treatment", "turbo tax", "turquoise", "twitter", "ultimate", 
		"unblocked", "underwear", "unique", "united states", "university", "unlimited", "unlock", "vagina", "vaginal", "valium", "vancouver", "vardenafil", "vehicle", "ventilation", "viagra", "video", "videography", 
		"vigara", "vigrx", "vintage", "visit now", "voucher", "wayfarer", "web page", "web site", "webmaster", "weight loss", "west virginia", "wholesale", "wicked", "wisconsin", "wyoming", "xanax", "xxx", 
		"youtube", "zimulti", "zithromax", "zoekmachine optimalisatie", 
		);
	return $blacklist_keyphrases;
	}
?>