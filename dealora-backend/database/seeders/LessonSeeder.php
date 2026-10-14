<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LessonSeeder extends Seeder
{
    public function run(): void
    {
        $course = Course::where(
            'slug',
            'sales-fundamentals'
        )->firstOrFail();

        $lessons = [
            [
                'title' => 'Introduction to Sales',
                'position' => 1,
                'duration_minutes' => 10,
                'content' => <<<'TEXT'
Welcome to Sales Fundamentals!

Sales is the process of understanding what someone needs and helping them find a suitable product or service.

Successful selling is not simply convincing people to buy something. It involves listening, solving problems, and building relationships.

For example, imagine you sell laptops. Instead of immediately recommending the most expensive laptop, ask the customer what they need it for.

A university student may need an affordable laptop for assignments, while a video editor may need a more powerful computer.

Key takeaway:
Great salespeople focus on customer needs before recommending solutions.
TEXT,
            ],
            [
                'title' => 'Understanding Customer Needs',
                'position' => 2,
                'duration_minutes' => 15,
                'content' => <<<'TEXT'
Before recommending a product, understand your customer.

Ask open-ended questions such as:
- What problem are you trying to solve?
- What features matter most to you?
- What is your budget?
- Have you tried another solution before?

Listen carefully and confirm that you understand their answers.

Example:
A customer wants to buy a phone. You discover they mainly need a long battery life and a good camera.

Instead of recommending a phone based only on price, show them options that match those priorities.

Key takeaway:
The better you understand your customer, the more relevant your recommendation becomes.
TEXT,
            ],
            [
                'title' => 'Building Trust with Customers',
                'position' => 3,
                'duration_minutes' => 15,
                'content' => <<<'TEXT'
Trust is essential in sales.

Customers are more comfortable buying from someone who is honest, respectful, and knowledgeable.

Ways to build trust:
- Give accurate product information.
- Be transparent about prices.
- Avoid promises you cannot keep.
- Respond to questions patiently.
- Follow up when you say you will.

Example:
If a product does not have a feature the customer needs, explain that clearly rather than hiding it.

Key takeaway:
Long-term customer relationships are more valuable than misleading someone into a single purchase.
TEXT,
            ],
            [
                'title' => 'Handling Customer Objections',
                'position' => 4,
                'duration_minutes' => 20,
                'content' => <<<'TEXT'
An objection is a concern that makes a customer hesitate before buying.

Common objections include:
- The price is too high.
- I need more time to decide.
- I am not sure this product will work.
- I found another option elsewhere.

Do not argue with the customer.

Instead:
1. Listen to their concern.
2. Ask questions to understand it.
3. Respond with relevant information.
4. Check whether the concern has been addressed.

Example:
Customer: "This laptop is too expensive."

You: "I understand. What budget were you hoping to stay within? We can compare a few options."

Key takeaway:
Treat objections as opportunities to understand the customer, not as arguments to win.
TEXT,
            ],
            [
                'title' => 'Closing Your First Deal',
                'position' => 5,
                'duration_minutes' => 20,
                'content' => <<<'TEXT'
Closing a deal means helping a customer make a purchasing decision.

A good closing process is clear and respectful.

Steps:
1. Summarize the customer's needs.
2. Explain how your solution addresses them.
3. Confirm the price and important terms.
4. Ask whether they are ready to proceed.
5. Explain the next steps.

Example:
"Based on what you told me, this laptop fits your budget and has the battery life you need. Would you like to go ahead with it?"

Never pressure someone into a purchase.

Key takeaway:
A successful close happens when the customer understands the value and feels ready to decide.

Congratulations! You have completed the introductory Sales Fundamentals content.
TEXT,
            ],
        ];

        foreach ($lessons as $lessonData) {
            $slug = Str::slug($lessonData['title']);

            Lesson::updateOrCreate(
                [
                    'course_id' => $course->id,
                    'slug' => $slug,
                ],
                [
                    'title' => $lessonData['title'],
                    'content' => $lessonData['content'],
                    'position' => $lessonData['position'],
                    'duration_minutes' => $lessonData['duration_minutes'],
                    'is_preview' => $lessonData['position'] === 1,
                    'is_published' => true,
                ]
            );
        }
    }
}
