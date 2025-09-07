<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UsefulResource;
use Illuminate\Support\Str;

class GenerateSlugs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'useful-resources:generate-slugs {--force : Force regenerate all slugs}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate slugs for existing useful resources';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting slug generation for useful resources...');
        
        $force = $this->option('force');
        
        // Slug'ı olmayan veya force ile tüm kayıtları al
        $query = UsefulResource::query();
        
        if (!$force) {
            $query->whereNull('slug')->orWhere('slug', '');
        }
        
        $resources = $query->get();
        
        if ($resources->isEmpty()) {
            $this->info('No resources found that need slug generation.');
            return 0;
        }
        
        $this->info("Found {$resources->count()} resources to process.");
        
        $progressBar = $this->output->createProgressBar($resources->count());
        $progressBar->start();
        
        $created = 0;
        $updated = 0;
        $errors = 0;
        
        foreach ($resources as $resource) {
            try {
                $originalSlug = $resource->slug;
                $newSlug = $this->generateUniqueSlug($resource->title, $resource->id);
                
                $resource->update(['slug' => $newSlug]);
                
                if (empty($originalSlug)) {
                    $created++;
                } else {
                    $updated++;
                }
                
            } catch (\Exception $e) {
                $errors++;
                $this->error("\nError processing '{$resource->title}': " . $e->getMessage());
            }
            
            $progressBar->advance();
        }
        
        $progressBar->finish();
        $this->newLine(2);
        
        // Sonuç raporu
        $this->info('Slug generation completed!');
        $this->table(
            ['Action', 'Count'],
            [
                ['Created', $created],
                ['Updated', $updated],
                ['Errors', $errors],
                ['Total', $created + $updated + $errors]
            ]
        );
        
        if ($errors > 0) {
            $this->warn("There were {$errors} errors during processing.");
        }
        
        // Örnekler göster
        $this->info("\nSample generated slugs:");
        $sampleResources = UsefulResource::whereNotNull('slug')
            ->limit(5)
            ->get(['title', 'slug']);
            
        foreach ($sampleResources as $sample) {
            $this->line("• {$sample->title} → {$sample->slug}");
        }
        
        return 0;
    }
    
    /**
     * Generate unique slug for the resource
     */
    private function generateUniqueSlug(string $title, int $excludeId = null): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $counter = 1;
        
        // Benzersizlik kontrolü
        while ($this->slugExists($slug, $excludeId)) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
    
    /**
     * Check if slug already exists
     */
    private function slugExists(string $slug, int $excludeId = null): bool
    {
        $query = UsefulResource::where('slug', $slug);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->exists();
    }
}