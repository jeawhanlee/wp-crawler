<?php

defined( 'ABSPATH' ) || exit;

?>

<div id="wpc_app">
    <div class="notice notice-success is-dismissible mt-3" v-if="feed">{{ feed }}</div>
	<div class="container mt-4">
		<h3>WP Crawler</h3>
		<div class="alert alert-secondary border-0 py-4" role="alert">
			<div v-if="loading">
				Loading...
			</div>
			<div v-else="">
				<div class="d-block mb-2">Last crawl was {{ crawl.last_crawl }}</div>
				<a href="javascript:void(0)" class="btn btn-primary" v-on:click="initCrawl">Run Crawler</a>

				<div class="mt-3" v-if="crawl.result.length">
					<h5>Latest Crawl Result</h5>
					<ul class="list-group list-group-flush">
						<li class="list-group-item" v-for="item in crawl.result">
							<a :href="item.link.includes('http') ? item.link : crawl.base + item.link" class="text-decoration-none" target="_blank">
							{{ item.text == '' ? item.link : item.text }}
							</a>
						</li>
					</ul>
				</div>
				<div v-else="">
					<div class="d-block mb-2">There is no crawl result to display at the moment!</div>
					<a href="javascript:void(0)" class="btn btn-primary" v-on:click="initCrawl">Run Crawler</a>
				</div>
			</div>
		</div>
	</div>
</div>
