<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App{
/**
 * App\TheoryQuestion
 *
 * @property int $id
 * @property string $question
 * @property int $theory_test_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\TheoryAnswer[] $theoryanswers
 * @property-read int|null $theoryanswers_count
 * @property-read \App\TheoryTest $theorytests
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TheoryQuestion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TheoryQuestion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TheoryQuestion query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TheoryQuestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TheoryQuestion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TheoryQuestion whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TheoryQuestion whereTheoryTestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TheoryQuestion whereUpdatedAt($value)
 */
	class TheoryQuestion extends \Eloquent {}
}

namespace App{
/**
 * App\Cbt
 *
 * @property int $id
 * @property int $objective_test_id
 * @property int $user_id
 * @property int|null $score
 * @property int|null $total
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ObjectiveAnswer[] $objectiveanswers
 * @property-read int|null $objectiveanswers_count
 * @property-read \App\ObjectiveTest $objectivetests
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cbt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cbt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cbt query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cbt whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cbt whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cbt whereObjectiveTestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cbt whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cbt whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cbt whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cbt whereUserId($value)
 */
	class Cbt extends \Eloquent {}
}

namespace App{
/**
 * App\ObjectiveAnswer
 *
 * @property int $id
 * @property int $cbt_id
 * @property int $objective_question_id
 * @property int $option_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Cbt $cbts
 * @property-read \App\ObjectiveQuestion $objectivequestions
 * @property-read \App\User $users
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ObjectiveAnswer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ObjectiveAnswer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ObjectiveAnswer query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ObjectiveAnswer whereCbtId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ObjectiveAnswer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ObjectiveAnswer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ObjectiveAnswer whereObjectiveQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ObjectiveAnswer whereOptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ObjectiveAnswer whereUpdatedAt($value)
 */
	class ObjectiveAnswer extends \Eloquent {}
}

namespace App{
/**
 * App\ObjectiveOption
 *
 * @property int $id
 * @property string $option
 * @property int $is_correct
 * @property int $objective_question_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\ObjectiveQuestion $objectivequestions
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ObjectiveOption newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ObjectiveOption newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ObjectiveOption query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ObjectiveOption whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ObjectiveOption whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ObjectiveOption whereIsCorrect($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ObjectiveOption whereObjectiveQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ObjectiveOption whereOption($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ObjectiveOption whereUpdatedAt($value)
 */
	class ObjectiveOption extends \Eloquent {}
}

namespace App{
/**
 * App\TheoryTest
 *
 * @property int $id
 * @property string $title
 * @property string $deadline
 * @property int $classroom_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Classroom $classrooms
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\TheoryQuestion[] $theoryquestions
 * @property-read int|null $theoryquestions_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TheoryTest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TheoryTest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TheoryTest query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TheoryTest whereClassroomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TheoryTest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TheoryTest whereDeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TheoryTest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TheoryTest whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TheoryTest whereUpdatedAt($value)
 */
	class TheoryTest extends \Eloquent {}
}

namespace App{
/**
 * App\TheoryAnswer
 *
 * @property int $id
 * @property string $answer
 * @property int $theory_question_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\TheoryQuestion $theoryquestions
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TheoryAnswer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TheoryAnswer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TheoryAnswer query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TheoryAnswer whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TheoryAnswer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TheoryAnswer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TheoryAnswer whereTheoryQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TheoryAnswer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TheoryAnswer whereUserId($value)
 */
	class TheoryAnswer extends \Eloquent {}
}

namespace App{
/**
 * App\ObjectiveTest
 *
 * @property int $id
 * @property string $title
 * @property string $starttime
 * @property int $duration
 * @property string $deadline
 * @property int $classroom_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Cbt[] $cbts
 * @property-read int|null $cbts_count
 * @property-read \App\Classroom $classrooms
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ObjectiveQuestion[] $objectivequestions
 * @property-read int|null $objectivequestions_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ObjectiveTest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ObjectiveTest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ObjectiveTest query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ObjectiveTest whereClassroomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ObjectiveTest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ObjectiveTest whereDeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ObjectiveTest whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ObjectiveTest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ObjectiveTest whereStarttime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ObjectiveTest whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ObjectiveTest whereUpdatedAt($value)
 */
	class ObjectiveTest extends \Eloquent {}
}

namespace App{
/**
 * App\ObjectiveQuestion
 *
 * @property int $id
 * @property string $question
 * @property int $objective_test_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ObjectiveAnswer[] $objectiveanswers
 * @property-read int|null $objectiveanswers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ObjectiveOption[] $objectiveoptions
 * @property-read int|null $objectiveoptions_count
 * @property-read \App\ObjectiveTest $objectivetests
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ObjectiveQuestion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ObjectiveQuestion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ObjectiveQuestion query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ObjectiveQuestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ObjectiveQuestion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ObjectiveQuestion whereObjectiveTestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ObjectiveQuestion whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ObjectiveQuestion whereUpdatedAt($value)
 */
	class ObjectiveQuestion extends \Eloquent {}
}

namespace App{
/**
 * App\Role
 *
 * @property int $id
 * @property string $role
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Role whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Role whereUpdatedAt($value)
 */
	class Role extends \Eloquent {}
}

namespace App{
/**
 * App\Organization
 *
 * @property int $id
 * @property string $name
 * @property string $address
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $code
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Environ[] $environs
 * @property-read int|null $environs_count
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Organization whereUserId($value)
 */
	class Organization extends \Eloquent {}
}

namespace App{
/**
 * App\Classroom
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property int $environ_id
 * @property int $user_id
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Environ $environs
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ObjectiveTest[] $objectivetests
 * @property-read int|null $objectivetests_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\TheoryTest[] $theorytests
 * @property-read int|null $theorytests_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Classroom newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Classroom newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Classroom query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Classroom whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Classroom whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Classroom whereEnvironId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Classroom whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Classroom whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Classroom whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Classroom whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Classroom whereUserId($value)
 */
	class Classroom extends \Eloquent {}
}

namespace App{
/**
 * App\User
 *
 * @property int $id
 * @property string $firstName
 * @property string $middleName
 * @property string $lastName
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $telephone
 * @property string $dateOfBirth
 * @property string $password
 * @property string|null $profile_image
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Classroom[] $classrooms
 * @property-read int|null $classrooms_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read int|null $clients_count
 * @property-read mixed $image
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ObjectiveAnswer[] $objectiveanswers
 * @property-read int|null $objectiveanswers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Organization[] $organizations
 * @property-read int|null $organizations_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\TheoryAnswer[] $theoryanswers
 * @property-read int|null $theoryanswers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereMiddleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereProfileImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereTelephone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App{
/**
 * App\Environ
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property int $organization_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Classroom[] $classrooms
 * @property-read int|null $classrooms_count
 * @property-read \App\Organization $organizations
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Environ newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Environ newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Environ query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Environ whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Environ whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Environ whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Environ whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Environ whereOrganizationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Environ whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Environ whereUserId($value)
 */
	class Environ extends \Eloquent {}
}

