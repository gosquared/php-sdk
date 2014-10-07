SITE_TOKEN ?= GSN-181546-E

test: test-event test-person test-transaction

test-event:
	SITE_TOKEN=$(SITE_TOKEN) test/event.php

test-person:
	SITE_TOKEN=$(SITE_TOKEN) test/person.php

test-transaction:
	SITE_TOKEN=$(SITE_TOKEN) test/transaction.php

.PHONY: test
