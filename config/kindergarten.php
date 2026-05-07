<?php

/**
 * Kindergarten Developmental Domains Configuration
 * Based on DepEd Contextualized Early Childhood Development (ECD) Checklist
 * Supports both Cebuano and English
 */

return [
    // Default language setting - can be 'cebuano' or 'english'
    'default_language' => 'cebuano',
    
    // Available languages
    'languages' => [
        'cebuano' => 'Cebuano/Bisaya',
        'english' => 'English',
    ],

    'domains' => [
        // DOMAIN 1: Kahimsog (Physical Health & Motor)
        'kahimsog' => [
            'name' => [
                'cebuano' => 'Kahimsog, Maayong Panglawas, ug Motor nga Kalambuan',
                'english' => 'Physical Health, Well-Being, and Motor Development',
            ],
            'indicators' => [
                'K1' => [
                    'cebuano' => 'Nagpakita ug naandang gawi sa kahimsog kun panglawas nga nag-alima sa iyang kalimpyo ug sanidad.',
                    'english' => 'Shows health habits that maintain cleanliness and sanitation.',
                ],
                'K2' => [
                    'cebuano' => 'Nagpakita ug pamatasan sa pagdasig sa kaugalingon nga lawas.',
                    'english' => 'Shows behavior in encouraging own body.',
                ],
                'K3' => [
                    'cebuano' => 'Nagpakita ug saktong locomotor nga kahanas ingon sa naglakaw, nagdagan, paglukso, pagkatkat atoll sa aktibidad sa dula, sayaw kun ehersusyo.',
                    'english' => 'Shows correct locomotor skills such as walking, running, jumping, climbing during play, dance or exercise activities.',
                ],
                'K4' => [
                    'cebuano' => 'Nagpakita ug saktong dili locomotor nga kahanas ingon sa pagtulod, pagbitad, pagliko, pagkiay-kiay, pagyuko ug uban pa atuol sa pagdula, sayaw kun ehersisyo.',
                    'english' => 'Shows correct non-locomotor skills such as pushing, pulling, turning, swaying, bending and others during play, dance or exercise.',
                ],
                'K5' => [
                    'cebuano' => 'Nagpakita ug pino takdo nga kahanas kinahanglan alang sa kaugalingon nga pagsipilyo, pagbutones, pagpahugot ug pagpaluag, pagsirado, ug paggamit sa kutsara ug tinidor.',
                    'english' => 'Shows fine motor skills needed for self-care: tooth brushing, buttoning, zipping, closing, and using spoon and fork.',
                ],
                'K6' => [
                    'cebuano' => 'Nagpakita ug tukma nga kahanas di motor kinahanglan alang sa mamugna ug kaugalingong pagpahayag saarte ingon sa paggisi, paggunting, pagpilit, pagkopya, pagdibuho ug drowing, pagbulok, paghulma, pagpintal, paglaso, pagtagkos ug uban pa.',
                    'english' => 'Shows fine motor skills needed for creative self-expression: tearing, cutting, pasting, copying, drawing, coloring, molding, painting, lacing, tying and others.',
                ],
            ],
        ],

        // DOMAIN 2: Mathematics
        'mathematics' => [
            'name' => [
                'cebuano' => 'Mathematics',
                'english' => 'Mathematical Thinking',
            ],
            'indicators' => [
                'M1' => [
                    'cebuano' => 'Makaila sa mga kolor.',
                    'english' => 'Identifies colors.',
                ],
                'M2' => [
                    'cebuano' => 'Makaila sa mga porma.',
                    'english' => 'Identifies shapes.',
                ],
                'M3' => [
                    'cebuano' => 'Makabahig sa mga butang pinaagi sa porma, gidak-on ug kolor.',
                    'english' => 'Classifies objects by shape, size and color.',
                ],
                'M4' => [
                    'cebuano' => 'Makakomparar ug makabahig sa mga butang pinaagi sa iyahang: gidak-on, gitas-on, gidaghanun ug gidugayon.',
                    'english' => 'Compares and classifies objects by size, height, quantity and length.',
                ],
                'M5' => [
                    'cebuano' => 'Makaila ug makadungog og pattern.',
                    'english' => 'Identifies and listens to patterns.',
                ],
                'M6' => [
                    'cebuano' => 'Makahingalan sa mga adlaw sa semana.',
                    'english' => 'Names the days of the week.',
                ],
                'M7' => [
                    'cebuano' => 'Makahingalan sa mga bulan sa tuig.',
                    'english' => 'Names the months of the year.',
                ],
                'M8' => [
                    'cebuano' => 'Makaila sa panahon sa adlaw ug makasulti sa oras gamit ang analog nga orasan.',
                    'english' => 'Identifies time of day and tells time using analog clock.',
                ],
                'M9' => [
                    'cebuano' => 'Makaihap hangtod sa biyente. Ang bata makaihap sugod sa 1 hangtud sa 20 (1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20)',
                    'english' => 'Counts up to twenty. The child can count from 1 to 20.',
                ],
                'M10' => [
                    'cebuano' => 'Makaihap og butang hangtod sa (10) napulo. Ang bata makaihap og butang sugod sa 1 hangtud sa 10 (1,2,3,4,5,6,7,8,9,10)',
                    'english' => 'Counts objects up to ten.',
                ],
                'M11' => [
                    'cebuano' => 'Makaila og numero 1 hangtod 10 (1,2,3,4,5,6,7,8,9,10).',
                    'english' => 'Recognizes numbers 1 to 10.',
                ],
                'M12' => [
                    'cebuano' => 'Makasulat og numero 1 hangtod 10 (1,2,3,4,5,6,7,8,9,10).',
                    'english' => 'Writes numbers 1 to 10.',
                ],
                'M13' => [
                    'cebuano' => 'Makapasunod sa mga numero.',
                    'english' => 'Follows number sequence.',
                ],
                'M14' => [
                    'cebuano' => 'Makaila sa bahig sa mga butan (e.g. 1st, 2nd, 3rd) sa grupo.',
                    'english' => 'Identifies ordinal position (1st, 2nd, 3rd) in a group.',
                ],
                'M15' => [
                    'cebuano' => 'Makasulbad sa simpleng addition problems.',
                    'english' => 'Solves simple addition problems.',
                ],
                'M16' => [
                    'cebuano' => 'Makasulbad sa simpleng subtraction problems.',
                    'english' => 'Solves simple subtraction problems.',
                ],
                'M17' => [
                    'cebuano' => 'Makahugpong sa mga butang nga pareho ang hidaghanon hangtod sa napulo (sinugdanan sa multiplication).',
                    'english' => 'Joins equal groups up to ten (beginning of multiplication).',
                ],
                'M18' => [
                    'cebuano' => 'Makabahin sa mga butang nga pareho ang hidaghanon hangtod sa napulo (sinugdanan sa division).',
                    'english' => 'Divides equal groups up to ten (beginning of division).',
                ],
                'M19' => [
                    'cebuano' => 'Makasunod sa gitas-on, kadaghanon, gibug-aton sa mga butang gamit ang dili igsulukod (non-standard).',
                    'english' => 'Follows height, quantity, weight of objects using non-standard measurement.',
                ],
                'M20' => [
                    'cebuano' => 'Makaila og sinsilyo ug papel nga kwarta (5c, 10c, 25c, 1p, 5p, 10p, 20p, 50p, 100p)',
                    'english' => 'Recognizes coins and bills (5c, 10c, 25c, 1p, 5p, 10p, 20p, 50p, 100p)',
                ],
            ],
        ],

        // DOMAIN 3: Kalikasan (Physical Environment)
        'kalikasan' => [
            'name' => [
                'cebuano' => 'Pagkasabot sa Kalikasan ug Pisikal nga Kinaiyahan',
                'english' => 'Understanding the Physical and Natural Environment',
            ],
            'indicators' => [
                'PE1' => [
                    'cebuano' => 'Makaila sa mga parte sa lawas ug ang iyang gamit o kagamitan.',
                    'english' => 'Identifies body parts and their uses.',
                ],
                'PE2' => [
                    'cebuano' => 'Makasulat sa mga obserbasyon nga adunay dibuho, numero ug simbolo.',
                    'english' => 'Writes observations with drawings, numbers and symbols.',
                ],
                'PE3' => [
                    'cebuano' => 'Makaila sa parte sa tanom ug hayopan.',
                    'english' => 'Identifies parts of plants and animals.',
                ],
                'PE4' => [
                    'cebuano' => 'Makabahin sa mga hayop pinaagi sa ilang pagkapareho og kinaiya o hitsura.',
                    'english' => 'Classifies animals by their similarities and characteristics.',
                ],
                'PE5' => [
                    'cebuano' => 'Makahulagway sa mga unang panginahanglan ug pag-alima sa mga tanom, hayupan ug palibot.',
                    'english' => 'Describes basic needs and care for plants, animals and environment.',
                ],
                'PE6' => [
                    'cebuano' => 'Makaila sa mga lain-laing klaseng panahon.',
                    'english' => 'Identifies different types of weather.',
                ],
            ],
        ],

        // DOMAIN 4: Receptive Language
        'receptive_lang' => [
            'name' => [
                'cebuano' => 'Pinulongan, Literasi ug Komunikasyon',
                'english' => 'Language, Literacy and Communication',
            ],
            'indicators' => [
                'RL1' => [
                    'cebuano' => 'Makaila sa mga elemento sa tingog, pananglitan sa gitas-on o kamubo-on sa tono og kakusgon o kahinayon niini.',
                    'english' => 'Identifies elements of sound such as pitch, tone loudness or softness.',
                ],
                'RL2' => [
                    'cebuano' => 'Makapaminaw nga may kaikag sa mga estorya, poems ug kanta nga gipaminaw.',
                    'english' => 'Listens with interest to stories, poems and songs.',
                ],
                'RL3' => [
                    'cebuano' => 'Makahinumdom sa mga detalye gikan sa mga estorya, poems ug kanta nga gipaminaw.',
                    'english' => 'Remembers details from stories, poems and songs listened to.',
                ],
                'RL4' => [
                    'cebuano' => 'Makasulti sa kaugalingong naagian mahitungod sa panghitabo sa estorya.',
                    'english' => 'Talks about own experience regarding story events.',
                ],
                'RL5' => [
                    'cebuano' => 'Makapasunod sa mga hitabo gikan sa estorya nga napaminaw.',
                    'english' => 'Follows events from the story listened to.',
                ],
                'RL6' => [
                    'cebuano' => 'Makabana bana sa kinaiya ug pagbati sa tawo.',
                    'english' => 'Notices character and feelings of people.',
                ],
                'RL7' => [
                    'cebuano' => 'Makaila sa simpleng hinungdan-sangputan ug problema-tubag sa nadungog nga estorya o sa kasagarang panghitabo.',
                    'english' => 'Identifies simple cause-effect and problem-solution in stories or common events.',
                ],
                'RL8' => [
                    'cebuano' => 'Makatagna sa sangputanan sa estorya.',
                    'english' => 'Predicts the outcome of the story.',
                ],
                'RL9' => [
                    'cebuano' => 'Makasulti sa kalainan o pagkapareho sa mga butang/litrato, sa kulang nga parte sa butang nga dili angay iuban sa hugpong.',
                    'english' => 'Tells differences or similarities of things/pictures, missing part that does not belong to the group.',
                ],
            ],
        ],

        // DOMAIN 5: Expressive Language
        'expressive_lang' => [
            'name' => [
                'cebuano' => 'Pagsulti/Expressive Language',
                'english' => 'Expressive Language',
            ],
            'indicators' => [
                'EL1' => [
                    'cebuano' => 'Mogamit sa mga insaktong panultihon ug matinahurong timbaya sa tukmang panahon.',
                    'english' => 'Uses correct words and polite greetings at the right time.',
                ],
                'EL2' => [
                    'cebuano' => 'Makasulti kabahin sa detalye sa butang, tawo ug uban pa nga tukma sa ipasabot.',
                    'english' => 'Speaks about details of things, people and others accurately.',
                ],
                'EL3' => [
                    'cebuano' => 'Dasig nga muapil sa mga buluhaton (pagsulti sa poems ug rhymes) ug pagtubag sa mga pangutana isip pag-inambitay.',
                    'english' => 'Actively participates in activities (reciting poems and rhymes) and answers questions for conversation.',
                ],
                'EL4' => [
                    'cebuano' => 'Mangutana gamit ang kinsa, unsa, asa, kanus-a, ug ngano.',
                    'english' => 'Asks questions using who, what, where, when, and why.',
                ],
                'EL5' => [
                    'cebuano' => 'Makahatag og 1-2 ka hut-ong sa pagsunod o direksyon.',
                    'english' => 'Gives 1-2 steps of sequence or direction.',
                ],
                'EL6' => [
                    'cebuano' => 'Makaestorya pag-usab sa mga panghitabo o makaasoy sa kaugalingong nasinati.',
                    'english' => 'Retells events or tells about own experiences.',
                ],
            ],
        ],

        // DOMAIN 6: Reading
        'reading' => [
            'name' => [
                'cebuano' => 'Pagbasa/Reading',
                'english' => 'Reading',
            ],
            'indicators' => [
                'PB1' => [
                    'cebuano' => 'Makaila sa tingog sa mga letra (gamit ang alpabeto nga binisaya). Ang bata makaila sa mga tingog sa mga letra: /a/ /b/ /c/ /d/ /e/ /f/ /g/ /h/ /i/ /j/ /k/ /l/ /m/ /n/ /ñ/ /o/ /p/ /q/ /r/ /s/ /t/ /u/ /v/ /w/ /x/ /y/ /z/',
                    'english' => 'Identifies letter sounds (using Bisaya alphabet). The child can identify sounds of letters.',
                ],
                'PB2' => [
                    'cebuano' => 'Makasulti sa dagko ug gamay nga mga letra (A-Z, a-z gamit ang alpabetong binisaya).',
                    'english' => 'Names uppercase and lowercase letters (A-Z, a-z using Bisaya alphabet).',
                ],
                'PB3' => [
                    'cebuano' => 'Makatukma sa dakong letra sa gamay nga letra gamit ang alpabetong binisaya.',
                    'english' => 'Matches uppercase to lowercase letters using Bisaya alphabet.',
                ],
                'PB4' => [
                    'cebuano' => 'Makaila sa unang tingog sa gihatag nga pulong.',
                    'english' => 'Identifies the beginning sound of a given word.',
                ],
                'PB5' => [
                    'cebuano' => 'Makaila sa mga pulong nga pareho og tingog.',
                    'english' => 'Identifies words with the same sound.',
                ],
                'PB6' => [
                    'cebuano' => 'Makaihap sa mga silaba sa gihatag nga pulong.',
                    'english' => 'Counts syllables in a given word.',
                ],
                'PB7' => [
                    'cebuano' => 'Makaila sa parte sa libro sama sa: Atubangan ug likod, title, tagasulat, tagadibuho ug uban pa.',
                    'english' => 'Identifies book parts: front and back, title, author, illustrator, etc.',
                ],
                'PB8' => [
                    'cebuano' => 'Makapakita og interes sa pagbasa pinaagi sa pagpakli sa mga pahina sa libro.',
                    'english' => 'Shows interest in reading by turning pages of the book.',
                ],
                'PB9' => [
                    'cebuano' => 'Makahatag ug kahulugan sa impormasyon gikan sa simpleng pictographs, mapa ug uban pa.',
                    'english' => 'Gives meaning to information from simple pictographs, maps, etc.',
                ],
            ],
        ],

        // DOMAIN 7: Writing
        'writing' => [
            'name' => [
                'cebuano' => 'Pagsulat/Writing',
                'english' => 'Writing',
            ],
            'indicators' => [
                'PS1' => [
                    'cebuano' => 'Makasulat sa iyang kaugalingon nga ngalan',
                    'english' => 'Writes own name.',
                ],
                'PS2' => [
                    'cebuano' => 'Makasulat sa gamay ug daku nga mga letra.',
                    'english' => 'Writes small and big letters.',
                ],
                'PS3' => [
                    'cebuano' => 'Makalitok sa mga simpleng ideya pinaagi sa mga simulo.',
                    'english' => 'Expresses simple ideas through sentences.',
                ],
            ],
        ],

        // DOMAIN 8: Socio-Emotional
        'sosyal' => [
            'name' => [
                'cebuano' => 'Kalambuang Sosyo-Emosyonal',
                'english' => 'Socio-Emotional Development',
            ],
            'indicators' => [
                'SE1' => [
                    'cebuano' => 'Makabungat sa kinaugalingong mga impormasyon.',
                    'english' => 'Provides personal information.',
                ],
                'SE2' => [
                    'cebuano' => 'Makalitok sa kaugalingong kaikag ug panginahanglan.',
                    'english' => 'Expresses own interests and needs.',
                ],
                'SE3' => [
                    'cebuano' => 'Nagpakita ug pagpangandam sa pagsulay og bag-ong kasinatian ug pagsalig sa kaugalingon sa pagbuhat og kaugalingong tahas.',
                    'english' => 'Shows readiness to try new experiences and self-confidence in doing own tasks.',
                ],
                'SE4' => [
                    'cebuano' => 'Makapakita sa gibati angay ug tarong nga paagi.',
                    'english' => 'Shows feelings in appropriate and proper manner.',
                ],
                'SE5' => [
                    'cebuano' => 'Makasunod sa mga balaod sa tunghaan ug makahimo sa mga tahas ug buluhaton niini.',
                    'english' => 'Follows school rules and can do tasks and activities.',
                ],
                'SE6' => [
                    'cebuano' => 'Makaila sa emosyon, makakita sa gibati sa uban ug makapakita og kaikag sa pagtabang.',
                    'english' => 'Identifies emotions, sees feelings of others and shows interest in helping.',
                ],
                'SE7' => [
                    'cebuano' => 'Makapahayag og respeto sa mga bata ug tigulang.',
                    'english' => 'Shows respect to children and adults.',
                ],
                'SE8' => [
                    'cebuano' => 'Makaila sa mga miyembro sa pamilya.',
                    'english' => 'Identifies family members.',
                ],
                'SE9' => [
                    'cebuano' => 'Makaila sa mga tawo ug mga lugar sa tunghaan ug komunidad.',
                    'english' => 'Identifies people and places in school and community.',
                ],
            ],
        ],
    ],

    'rating_scale' => [
        'B' => [
            'label' => [
                'cebuano' => 'Sinugdan',
                'english' => 'Beginning',
            ],
            'description' => [
                'cebuano' => 'Kuwang pa ang pagpakita sa kahanas',
                'english' => 'Rarely demonstrates the expected competency',
            ],
        ],
        'D' => [
            'label' => [
                'cebuano' => 'Nagpalambo',
                'english' => 'Developing',
            ],
            'description' => [
                'cebuano' => 'Usahay makapakita og kahanas',
                'english' => 'Sometimes demonstrates the competency',
            ],
        ],
        'C' => [
            'label' => [
                'cebuano' => 'Kusgan',
                'english' => 'Consistent',
            ],
            'description' => [
                'cebuano' => 'Kanunay makapakita og kahanas',
                'english' => 'Always demonstrates the expected competency',
            ],
        ],
    ],
];
