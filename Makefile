SITE_TOKEN ?= GSN-181546-E
API_KEY ?= demo

test: test-tracking test-reporting
test-tracking: test-event test-person test-transaction
test-reporting: test-now test-trends test-ecommerce test-account

test-event:
	SITE_TOKEN=$(SITE_TOKEN) API_KEY=$(API_KEY) test/event.php

test-person:
	SITE_TOKEN=$(SITE_TOKEN) API_KEY=$(API_KEY) test/person.php

test-transaction:
	SITE_TOKEN=$(SITE_TOKEN) API_KEY=$(API_KEY) test/transaction.php

test-now:
	SITE_TOKEN=$(SITE_TOKEN) API_KEY=$(API_KEY) test/now.php

test-trends:
	SITE_TOKEN=$(SITE_TOKEN) API_KEY=$(API_KEY) test/trends.php

test-ecommerce:
	SITE_TOKEN=$(SITE_TOKEN) API_KEY=$(API_KEY) test/ecommerce.php

test-account:
	SITE_TOKEN=$(SITE_TOKEN) API_KEY=$(API_KEY) test/account.php

.PHONY: test
