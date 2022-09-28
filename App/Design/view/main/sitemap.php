<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<?php
$baseUrl = rtrim(str_replace('\\', '/', get_base_url()), '/');
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc><?= $baseUrl . url('home.index')->getRelativeUrlTrimmed(); ?></loc>
        <lastmod><?= (new DateTime())->format(SITEMAP_DATETIME_FORMAT); ?></lastmod>
        <changefreq><?= SITEMAP_CHANGE_FREQ_ALWAYS; ?></changefreq>
        <priority><?= SITEMAP_PRIORITY_1_0; ?></priority>
    </url>

    <url>
        <loc><?= $baseUrl . url('home.about')->getRelativeUrlTrimmed(); ?></loc>
        <lastmod><?= (new DateTime())->format(SITEMAP_DATETIME_FORMAT); ?></lastmod>
        <changefreq><?= SITEMAP_CHANGE_FREQ_MONTHLY; ?></changefreq>
        <priority><?= SITEMAP_PRIORITY_0_6; ?></priority>
    </url>

    <url>
        <loc><?= $baseUrl . url('home.faq')->getRelativeUrlTrimmed(); ?></loc>
        <lastmod><?= (new DateTime())->format(SITEMAP_DATETIME_FORMAT); ?></lastmod>
        <changefreq><?= SITEMAP_CHANGE_FREQ_MONTHLY; ?></changefreq>
        <priority><?= SITEMAP_PRIORITY_0_6; ?></priority>
    </url>

    <url>
        <loc><?= $baseUrl . url('home.contact')->getRelativeUrlTrimmed(); ?></loc>
        <lastmod><?= (new DateTime())->format(SITEMAP_DATETIME_FORMAT); ?></lastmod>
        <changefreq><?= SITEMAP_CHANGE_FREQ_YEARLY; ?></changefreq>
        <priority><?= SITEMAP_PRIORITY_0_3; ?></priority>
    </url>

    <url>
        <loc><?= $baseUrl . url('home.complaint')->getRelativeUrlTrimmed(); ?></loc>
        <lastmod><?= (new DateTime())->format(SITEMAP_DATETIME_FORMAT); ?></lastmod>
        <changefreq><?= SITEMAP_CHANGE_FREQ_NEVER; ?></changefreq>
        <priority><?= SITEMAP_PRIORITY_0_1; ?></priority>
    </url>

    <?php foreach ($pages ?? [] as $page): ?>
        <url>
            <loc><?= $baseUrl . url('home.pages', ['url' => $page['url']])->getRelativeUrlTrimmed() ?></loc>
            <lastmod><?= (new DateTime())->setTimestamp($page['updated_at'] ?? $page['created_at'])->format(SITEMAP_DATETIME_FORMAT); ?></lastmod>
            <changefreq><?= SITEMAP_CHANGE_FREQ_MONTHLY; ?></changefreq>
            <priority><?= SITEMAP_PRIORITY_0_5; ?></priority>
        </url>
    <?php endforeach; ?>

    <url>
        <loc><?= $baseUrl . url('home.login')->getRelativeUrlTrimmed(); ?></loc>
        <lastmod><?= (new DateTime())->format(SITEMAP_DATETIME_FORMAT); ?></lastmod>
        <changefreq><?= SITEMAP_CHANGE_FREQ_YEARLY; ?></changefreq>
        <priority><?= SITEMAP_PRIORITY_1_0; ?></priority>
    </url>

    <url>
        <loc><?= $baseUrl . url('home.forget-password')->getRelativeUrlTrimmed(); ?></loc>
        <lastmod><?= (new DateTime())->format(SITEMAP_DATETIME_FORMAT); ?></lastmod>
        <changefreq><?= SITEMAP_CHANGE_FREQ_YEARLY; ?></changefreq>
        <priority><?= SITEMAP_PRIORITY_0_5; ?></priority>
    </url>

    <url>
        <loc><?= $baseUrl . url('home.signup')->getRelativeUrlTrimmed(); ?></loc>
        <lastmod><?= (new DateTime())->format(SITEMAP_DATETIME_FORMAT); ?></lastmod>
        <changefreq><?= SITEMAP_CHANGE_FREQ_YEARLY; ?></changefreq>
        <priority><?= SITEMAP_PRIORITY_1_0; ?></priority>
    </url>

    <url>
        <loc><?= $baseUrl . url('home.cart')->getRelativeUrlTrimmed(); ?></loc>
        <lastmod><?= (new DateTime())->format(SITEMAP_DATETIME_FORMAT); ?></lastmod>
        <changefreq><?= SITEMAP_CHANGE_FREQ_YEARLY; ?></changefreq>
        <priority><?= SITEMAP_PRIORITY_0_6; ?></priority>
    </url>

    <url>
        <loc><?= $baseUrl . url('home.search')->getRelativeUrlTrimmed(); ?></loc>
        <lastmod><?= (new DateTime())->format(SITEMAP_DATETIME_FORMAT); ?></lastmod>
        <changefreq><?= SITEMAP_CHANGE_FREQ_DAILY; ?></changefreq>
        <priority><?= SITEMAP_PRIORITY_1_0; ?></priority>
    </url>

    <?php foreach ($products ?? [] as $product): ?>
        <url>
            <loc><?= $baseUrl . url('home.product.show', ['id' => $product['id'], 'slug' => $product['slug']])->getRelativeUrlTrimmed(); ?></loc>
            <lastmod><?= (new DateTime())->setTimestamp($product['updated_at'] ?? $product['created_at'])->format(SITEMAP_DATETIME_FORMAT); ?></lastmod>
            <changefreq><?= SITEMAP_CHANGE_FREQ_WEEKLY; ?></changefreq>
            <priority><?= SITEMAP_PRIORITY_1_0; ?></priority>
        </url>
    <?php endforeach; ?>

    <url>
        <loc><?= $baseUrl . url('home.blog')->getRelativeUrlTrimmed(); ?></loc>
        <lastmod><?= (new DateTime())->format(SITEMAP_DATETIME_FORMAT); ?></lastmod>
        <changefreq><?= SITEMAP_CHANGE_FREQ_DAILY; ?></changefreq>
        <priority><?= SITEMAP_PRIORITY_0_8; ?></priority>
    </url>

    <?php foreach ($blogs ?? [] as $blog): ?>
        <url>
            <loc><?= $baseUrl . url('home.blog.show', ['id' => $blog['id'], 'slug' => $blog['slug']])->getRelativeUrlTrimmed(); ?></loc>
            <lastmod><?= (new DateTime())->setTimestamp($blog['updated_at'] ?? $blog['created_at'])->format(SITEMAP_DATETIME_FORMAT); ?></lastmod>
            <changefreq><?= SITEMAP_CHANGE_FREQ_WEEKLY; ?></changefreq>
            <priority><?= SITEMAP_PRIORITY_1_0; ?></priority>
        </url>
    <?php endforeach; ?>
</urlset>